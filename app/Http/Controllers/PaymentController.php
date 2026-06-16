<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Pesanan;
use App\Models\barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Tampilkan halaman pembayaran & generate Snap Token
     */
//     public function show($id)
//     {
//         $custom = CustomOrder::findOrFail($id);

//         // CONFIG MIDTRANS
// Config::$serverKey = env('MIDTRANS_SERVER_KEY'); 
//                 Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); 
//                 Config::$isSanitized = true;
//                 Config::$is3ds = true;

//                $params = array(
//                     'transaction_details' => array(
//                         'order_id' => 'CUSTOM-' . $custom->id . '-' . time(),
//                         'gross_amount' => (int) $custom->estimasi_harga,
//                     ),

//                     'customer_details' => array(
//                         'first_name' => auth()->user()->name,
//                         'email' => auth()->user()->email ?? 'pelanggan@example.com',
//                     ),

//                     'item_details' => array(
//                         array(
//                             'id' => 'CUSTOM-' . $custom->id,
//                             'price' => (int) $custom->estimasi_harga,
//                             'quantity' => 1,
//                             'name' => $custom->jenis_furniture,
//                         )
//                     )
//                 );

//                 $snapToken = Snap::getSnapToken($params);

//         $custom->update([
//             'snap_token'        => $snapToken,
//             'midtrans_order_id' => $midtransOrderId,
//         ]);

//         return view('payment.show', compact('custom', 'snapToken'));
//     }
    public function show($id)
    {
        $custom = CustomOrder::findOrFail($id);

        if ($custom->payment_status === 'paid') {
            return redirect()->route('profile')
                ->with('success', 'Pembayaran custom order sudah selesai.');
        }

        // Cek apakah estimasi harga sudah ada
        if (!$custom->estimasi_harga || $custom->estimasi_harga <= 0) {
            return redirect()->route('profile')
                ->with('error', 'Estimasi harga belum tersedia.');
        }

        // Jika belum punya Snap Token, buat baru
        if (!$custom->snap_token) {

            $midtransOrderId = 'CUSTOM-' . $custom->id . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId,
                    'gross_amount' => (int) $custom->estimasi_harga,
                ],

                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],

                'item_details' => [
                    [
                        'id' => 'CUSTOM-' . $custom->id,
                        'price' => (int) $custom->estimasi_harga,
                        'quantity' => 1,
                        'name' => $custom->jenis_furniture,
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            $custom->update($this->customPaymentUpdate([
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $midtransOrderId,
                'payment_status'    => 'pending',
            ]));
        } else {

            $snapToken = $custom->snap_token;
        }

        return view('payment.show', compact('custom', 'snapToken'));
    }
    /**
     * Callback / Webhook dari Midtrans
     */
   public function callback(Request $request)
  {
      $notif = new Notification();

      $orderId           = $notif->order_id;
      $transactionStatus = $notif->transaction_status;
      $fraudStatus       = $notif->fraud_status ?? null;
      $transactionId     = $notif->transaction_id ?? null;
      $paymentType       = $notif->payment_type ?? null;
      $paymentMethod     = $this->resolveMidtransPaymentMethod($notif, $paymentType);

      if ($transactionStatus === 'capture') {
          $paymentStatus = ($fraudStatus === 'accept') ? 'paid' : 'failed';
      } elseif ($transactionStatus === 'settlement') {
          $paymentStatus = 'paid';
      } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
          $paymentStatus = 'failed';
      } elseif ($transactionStatus === 'pending') {
          $paymentStatus = 'pending';
      } else {
          $paymentStatus = 'unknown';
      }

      if (str_starts_with($orderId, 'CUSTOM-')) {
          $custom = CustomOrder::where('midtrans_order_id', $orderId)->firstOrFail();

          if ($paymentStatus === 'paid') {
              $this->setCustomPaid($custom, $transactionId);
          } else {
              $custom->update($this->customPaymentUpdate([
                  'payment_status' => $paymentStatus,
                  'transaction_id' => $transactionId,
              ]));
          }

          return response()->json([
              'message' => 'Callback custom berhasil diproses'
          ]);
      }

      if (str_starts_with($orderId, 'ORD-')) {
        $pesanan = Pesanan::with('detail.barang')
            ->where('kode', $orderId)
            ->firstOrFail();

        if ($paymentStatus === 'paid') {
            $this->setRegularPaid($pesanan, $paymentMethod, $transactionId);
        } else {
            $pesanan->update([
                'payment_status' => $paymentStatus,
                'metode_pembayaran' => $paymentMethod ?: $pesanan->metode_pembayaran ?: 'midtrans',
                'transaction_id' => $transactionId,
            ]);
        }

        return response()->json([
            'message' => 'Callback pesanan berhasil diproses'
        ]);
    }

      return response()->json([
          'message' => 'Order ID tidak dikenali'
      ], 404);
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

    private function setRegularPaid($pesanan, $paymentMethod = null, $transactionId = null)
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

    private function needsPaymentMethodSync($pesanan)
    {
        return !$pesanan->metode_pembayaran || $pesanan->metode_pembayaran === 'midtrans';
    }

    /**
     * Redirect sukses dari Midtrans
     */
    // public function success($id)
    // {
    //     return redirect()->route('profile')
    //         ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    // }
    public function success($id)
    {
        $custom = CustomOrder::findOrFail($id);

        $this->setCustomPaid($custom);

        return redirect()->route('profile')
            ->with('success', 'Pembayaran berhasil.');
    }
    /**
     * Redirect gagal / cancel dari Midtrans
     */
    public function failed($id)
    {
        return redirect()->route('profile')
            ->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
    }
}
