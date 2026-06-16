<?php

  namespace App\Http\Controllers\Api;

  use App\Http\Controllers\Controller;
  use App\Models\Pesanan;
  use App\Models\CustomOrder;
  use App\Models\barang;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\DB;
  use Midtrans\Config;
  use Midtrans\Snap;

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
          $orderId = 'ORD-' . $pesanan->id . '-' . time();

          $pesanan->update([
                'kode' => $orderId,
                'jumlah_harga' => $total,
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
          $custom = CustomOrder::where('user_id', auth()->id())
              ->findOrFail($id);

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

          $orderId = 'CUSTOM-' . $custom->id . '-' . time();

          $this->midtransConfig();

          $transaction = Snap::createTransaction([
              'transaction_details' => [
                  'order_id' => $orderId,
                  'gross_amount' => (int) $custom->estimasi_harga,
              ],
              'customer_details' => [
                  'first_name' => auth()->user()->name,
                  'email' => auth()->user()->email,
              ],
              'item_details' => [[
                  'id' => 'CUSTOM-' . $custom->id,
                  'price' => (int) $custom->estimasi_harga,
                  'quantity' => 1,
                  'name' => $custom->jenis_furniture,
              ]],
          ]);

          $custom->update([
              'snap_token' => $transaction->token,
              'midtrans_order_id' => $orderId,
              'payment_status' => 'pending',
          ]);

          return response()->json([
              'order_id' => $orderId,
              'snap_token' => $transaction->token,
              'redirect_url' => $transaction->redirect_url,
          ]);
      }

      public function status($orderId)
      {
          if (str_starts_with($orderId, 'CUSTOM-')) {
              $custom = CustomOrder::where('midtrans_order_id', $orderId)->first();

              return response()->json([
                  'type' => 'custom',
                  'order_id' => $orderId,
                  'payment_status' => $custom?->payment_status,
                  'status' => $custom?->status,
              ]);
          }

          $pesanan = Pesanan::where('kode', $orderId)->first();

          return response()->json([
              'type' => 'regular',
              'order_id' => $orderId,
              'payment_status' => $pesanan?->payment_status,
              'status' => $pesanan?->status,
          ]);
      }
  }