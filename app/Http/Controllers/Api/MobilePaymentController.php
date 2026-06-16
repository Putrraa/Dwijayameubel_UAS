<?php

  namespace App\Http\Controllers\Api;

  use App\Http\Controllers\Controller;
  use App\Models\Pesanan;
  use App\Models\CustomOrder;
  use App\Models\barang;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\DB;
  use Illuminate\Support\Facades\Schema;
  use Midtrans\Config;
  use Midtrans\Snap;
  use Midtrans\Transaction;

  class MobilePaymentController extends Controller
  {
      private function midtransConfig()
      {
          Config::$serverKey = config('midtrans.server_key');
          Config::$isProduction = config('midtrans.is_production');
          Config::$isSanitized = config('midtrans.is_sanitized');
          Config::$is3ds = config('midtrans.is_3ds');
      }

      public function payRegular(Request $request)
      {
          $request->validate([
                'user_id' => 'required|integer',
                'nama_penerima' => 'required|string',
                'no_telepon' => 'required|string',
                'alamat' => 'required|string',
                'kota' => 'required|string',
                'kode_pos' => 'required|string',
                'catatan' => 'nullable|string',
            ]);

          $pesanan = Pesanan::with('detail.barang')
              ->where('user_id', $request->user_id)
              ->where('status', 0)
              ->first();

          if (!$pesanan || $pesanan->detail->count() < 1) {
              return response()->json([
                  'message' => 'Keranjang masih kosong.'
              ], 422);
          }

          foreach ($pesanan->detail as $item) {
              if ($item->barang->stok < $item->jumlah) {
                  return response()->json([
                      'message' => 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi.'
                  ], 422);
              }
          }

          $total = $pesanan->detail->sum('jumlah_harga');
          $orderId = 'ORD-' . $pesanan->id . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

          $pesanan->update([
                'kode' => $orderId,
                'jumlah_harga' => $total,
                'status' => 1,
                'nama_penerima' => $request->nama_penerima,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'kode_pos' => $request->kode_pos,
                'catatan' => $request->catatan,
                'payment_status' => 'pending',
            ]);

          $itemDetails = [];

          foreach ($pesanan->detail as $item) {
              $itemDetails[] = [
                  'id' => $item->barang->id,
                  'price' => (int) $item->barang->harga,
                  'quantity' => (int) $item->jumlah,
                  'name' => $item->barang->nama_barang,
              ];
          }

          $this->midtransConfig();

          $transaction = Snap::createTransaction([
              'transaction_details' => [
                  'order_id' => $orderId,
                  'gross_amount' => (int) $total,
              ],
              'enabled_payments' => [
                  'bank_transfer',
                  'gopay',
                  'qris',
              ],
              'customer_details' => [
                  'first_name' => $request->nama_penerima,
                  'phone' => $request->no_telepon,
              ],
              'item_details' => $itemDetails,
          ]);

          $pesanan->update([
              'snap_token' => $transaction->token,
          ]);

          return response()->json([
                'status' => true,
                'message' => 'Transaksi Midtrans berhasil dibuat',
                'order_id' => $orderId,
                'snap_token' => $transaction->token,
                'redirect_url' => $transaction->redirect_url,
            ]);
      }

      public function payCustom($id)
      {
          $custom = CustomOrder::with('user')->findOrFail($id);

          if (!$custom->estimasi_harga || $custom->estimasi_harga <= 0) {
              return response()->json([
                  'message' => 'Estimasi harga belum tersedia.'
              ], 422);
          }

          if ($custom->payment_status === 'paid') {
              return response()->json([
                  'message' => 'Pesanan custom ini sudah dibayar.'
              ], 422);
          }

          $orderId = 'CUSTOM-' . $custom->id . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

          $this->midtransConfig();

          $transaction = Snap::createTransaction([
              'transaction_details' => [
                  'order_id' => $orderId,
                  'gross_amount' => (int) $custom->estimasi_harga,
              ],
              'customer_details' => [
                  'first_name' => $custom->user->name ?? 'Customer',
                  'email' => $custom->user->email ?? 'customer@example.com',
              ],
              'item_details' => [[
                  'id' => 'CUSTOM-' . $custom->id,
                  'price' => (int) $custom->estimasi_harga,
                  'quantity' => 1,
                  'name' => $custom->jenis_furniture,
              ]],
          ]);

          $custom->update($this->customPaymentUpdate([
              'snap_token' => $transaction->token,
              'midtrans_order_id' => $orderId,
              'payment_status' => 'pending',
          ]));

          return response()->json([
              'status' => true,
              'message' => 'Pembayaran custom berhasil dibuat',
              'order_id' => $orderId,
              'snap_token' => $transaction->token,
              'redirect_url' => $transaction->redirect_url,
          ]);
      }

      public function status($orderId)
      {
          if (str_starts_with($orderId, 'CUSTOM-')) {
              $custom = CustomOrder::where('midtrans_order_id', $orderId)->first();

              if ($custom && $custom->payment_status !== 'paid') {
                  $this->syncCustomPaymentFromMidtrans($custom);
                  $custom->refresh();
              }

              return response()->json([
                  'type' => 'custom',
                  'order_id' => $orderId,
                  'payment_status' => $custom?->payment_status,
                  'status' => $custom?->status,
              ]);
          }

          $pesanan = Pesanan::where('kode', $orderId)->first();

          if ($pesanan && ($pesanan->payment_status !== 'paid' || $this->needsPaymentMethodSync($pesanan))) {
              $this->syncRegularPaymentFromMidtrans($pesanan);
              $pesanan->refresh();
          }

          return response()->json([
              'type' => 'regular',
              'order_id' => $orderId,
              'payment_status' => $pesanan?->payment_status,
              'status' => $pesanan?->status,
          ]);
      }

      private function syncRegularPaymentFromMidtrans(Pesanan $pesanan)
      {
          try {
              $this->midtransConfig();
              $midtransStatus = Transaction::status($pesanan->kode);
          } catch (\Exception $e) {
              return;
          }

          $transactionStatus = $midtransStatus->transaction_status ?? null;
          $fraudStatus = $midtransStatus->fraud_status ?? null;
          $paymentType = $midtransStatus->payment_type ?? null;
          $paymentMethod = $this->resolveMidtransPaymentMethod($midtransStatus, $paymentType);
          $transactionId = $midtransStatus->transaction_id ?? null;

          if ($transactionStatus === 'capture') {
              if ($fraudStatus === 'accept') {
                  $this->setRegularPaid($pesanan, $paymentMethod, $transactionId);
              }
          } elseif ($transactionStatus === 'settlement') {
              $this->setRegularPaid($pesanan, $paymentMethod, $transactionId);
          } elseif ($transactionStatus === 'pending') {
              $pesanan->update([
                  'payment_status' => 'pending',
                  'metode_pembayaran' => $paymentMethod ?: $pesanan->metode_pembayaran ?: 'midtrans',
                  'transaction_id' => $transactionId,
              ]);
          } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
              $pesanan->update([
                  'payment_status' => 'failed',
                  'metode_pembayaran' => $paymentMethod ?: $pesanan->metode_pembayaran ?: 'midtrans',
                  'transaction_id' => $transactionId,
              ]);
          }
      }

      private function syncCustomPaymentFromMidtrans(CustomOrder $custom)
      {
          try {
              $this->midtransConfig();
              $midtransStatus = Transaction::status($custom->midtrans_order_id);
          } catch (\Exception $e) {
              return;
          }

          $transactionStatus = $midtransStatus->transaction_status ?? null;
          $fraudStatus = $midtransStatus->fraud_status ?? null;
          $transactionId = $midtransStatus->transaction_id ?? null;

          if ($transactionStatus === 'capture') {
              if ($fraudStatus === 'accept') {
                  $this->setCustomPaid($custom, $transactionId);
              }
          } elseif ($transactionStatus === 'settlement') {
              $this->setCustomPaid($custom, $transactionId);
          } elseif ($transactionStatus === 'pending') {
              $custom->update($this->customPaymentUpdate([
                  'payment_status' => 'pending',
                  'transaction_id' => $transactionId,
              ]));
          } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
              $custom->update($this->customPaymentUpdate([
                  'payment_status' => 'failed',
                  'transaction_id' => $transactionId,
              ]));
          }
      }

      private function setCustomPaid(CustomOrder $custom, $transactionId = null)
      {
          $custom->refresh();

          if ($custom->payment_status === 'paid') {
              return;
          }

          $custom->update($this->customPaymentUpdate([
              'payment_status' => 'paid',
              'transaction_id' => $transactionId,
              'status' => 'diproses',
          ]));
      }

      private function customPaymentUpdate(array $data)
      {
          if (Schema::hasColumn('custom_orders', 'metode_pembayaran')) {
              $data['metode_pembayaran'] = 'midtrans';
          }

          return $data;
      }

      private function setRegularPaid(Pesanan $pesanan, $paymentMethod = null, $transactionId = null)
      {
          $pesanan->refresh();

          if ($pesanan->payment_status === 'paid') {
              if ($this->needsPaymentMethodSync($pesanan) && $paymentMethod && $paymentMethod !== 'midtrans') {
                  $pesanan->update([
                      'metode_pembayaran' => $paymentMethod,
                      'transaction_id' => $transactionId ?: $pesanan->transaction_id,
                  ]);
              }

              return;
          }

          DB::transaction(function () use ($pesanan, $paymentMethod, $transactionId) {
              $pesanan = Pesanan::with('detail')
                  ->lockForUpdate()
                  ->findOrFail($pesanan->id);

              if ($pesanan->payment_status === 'paid') {
                  return;
              }

              foreach ($pesanan->detail as $item) {
                  $barang = barang::lockForUpdate()->findOrFail($item->barang_id);

                  if ($barang->stok < $item->jumlah) {
                      throw new \Exception('Stok ' . $barang->nama_barang . ' tidak mencukupi.');
                  }

                  $barang->stok -= $item->jumlah;
                  $barang->save();
              }

              $pesanan->update([
                  'status' => 1,
                  'payment_status' => 'paid',
                  'metode_pembayaran' => $paymentMethod ?: $pesanan->metode_pembayaran ?: 'midtrans',
                  'transaction_id' => $transactionId,
                  'paid_at' => now(),
              ]);
          });
      }

      private function resolveMidtransPaymentMethod($source, $fallback = null)
      {
          $paymentType = $source->payment_type ?? $fallback;

          if ($paymentType === 'bank_transfer') {
              $bank = null;

              if (!empty($source->va_numbers) && isset($source->va_numbers[0]->bank)) {
                  $bank = $source->va_numbers[0]->bank;
              } elseif (!empty($source->permata_va_number)) {
                  $bank = 'permata';
              }

              if ($bank) {
                  return strtolower($bank) . '_va';
              }
          }

          return $paymentType ?: 'midtrans';
      }

      private function needsPaymentMethodSync(Pesanan $pesanan)
      {
          return !$pesanan->metode_pembayaran || $pesanan->metode_pembayaran === 'midtrans';
      }
  }
