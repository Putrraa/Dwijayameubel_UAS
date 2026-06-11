<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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

        // Cek apakah estimasi harga sudah ada
        if (!$custom->estimasi_harga || $custom->estimasi_harga <= 0) {
            return redirect()->route('profile')
                ->with('error', 'Estimasi harga belum tersedia.');
        }

        // Jika belum punya Snap Token, buat baru
        if (!$custom->snap_token) {

            $midtransOrderId = 'CUSTOM-' . $custom->id . '-' . time();

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

            $custom->update([
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $midtransOrderId,
            ]);
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
        $fraudStatus       = $notif->fraud_status;
        $transactionId     = $notif->transaction_id;

        $custom = CustomOrder::where('midtrans_order_id', $orderId)->firstOrFail();

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

        $custom->update([
            'payment_status' => $paymentStatus,
            'transaction_id' => $transactionId,
            'status'         => ($paymentStatus === 'paid') ? 'selesai' : $custom->status,
        ]);

        return response()->json(['message' => 'OK']);
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

        $custom->update([
            'payment_status' => 'paid'
        ]);

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