<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\Pesanan;
use App\Models\detail_pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class pesanancontroller extends Controller
{
// 1. Menampilkan halaman pesanan reguler ke kasir
public function pesanan()
{
    // Mengambil pesanan yang bukan lagi di dalam keranjang (status 1, 2, 3, 4)
    $data_pesanan = pesanan::where('status', '>', 0)
        ->orderBy('updated_at', 'desc')
        ->get();
    
    // Mengarahkan ke file blade pesanan reguler
    return view('kasir.pesanan', compact('data_pesanan'));
}

// 2. Memproses update status pesanan dari select dropdown kasir
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|integer|in:1,2,3,4',
    ]);

    $pesanan = pesanan::findOrFail($id);
    $pesanan->status = $request->status;
    $pesanan->save();

    return redirect()->back()->with('success', 'Status pesanan #' . $pesanan->kode . ' berhasil diperbarui!');
}
    public function quickAdd(Request $request, $id)
    {
        $jumlahPesan = max(1, (int) $request->input('quantity', 1));
        $barang = barang::findOrFail($id);

        if ($jumlahPesan > $barang->stok) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        $pesanan = pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            [
                'tanggal'      => now(),
                'jumlah_harga' => 0,
                'kode'         => 'ORD-' . time()
            ]
        );

        $detail = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->where('barang_id', $barang->id)
            ->first();

        if ($detail) {
            $detail->jumlah       += $jumlahPesan;
            $detail->jumlah_harga += $barang->harga * $jumlahPesan;
            $detail->save();
        } else {
            detail_pesanan::create([
                'pesanan_id'   => $pesanan->id,
                'barang_id'    => $barang->id,
                'jumlah'       => $jumlahPesan,
                'jumlah_harga' => $barang->harga * $jumlahPesan
            ]);
        }

        $pesanan->jumlah_harga = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->sum('jumlah_harga');
        $pesanan->save();

        return redirect()->route('customer.checkout')
            ->with('success', 'Barang berhasil masuk keranjang');
    }

    public function pesan(Request $request, $id)
    {
        $request->validate([
            'jumlah_pesan' => 'required|integer|min:1'
        ]);

        $barang = barang::findOrFail($id);

        if ($request->jumlah_pesan > $barang->stok) {
            return back()->with('error', 'Stok tidak cukup');
        }

        $pesanan = pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 0],
            [
                'tanggal' => now(),
                'jumlah_harga' => 0,
                'kode' => 'ORD-' . time()
            ]
        );

        $detail = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->where('barang_id', $barang->id)
            ->first();

        if ($detail) {
            $detail->jumlah += $request->jumlah_pesan;
            $detail->jumlah_harga += $barang->harga * $request->jumlah_pesan;
            $detail->save();
        } else {
            detail_pesanan::create([
                'pesanan_id' => $pesanan->id,
                'barang_id' => $barang->id,
                'jumlah' => $request->jumlah_pesan,
                'jumlah_harga' => $barang->harga * $request->jumlah_pesan
            ]);
        }

        $pesanan->jumlah_harga = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->sum('jumlah_harga');
        $pesanan->save();

        return redirect()->route('customer.checkout')
            ->with('success', 'Berhasil masuk keranjang');
    }

    public function tambah($id)
    {
        $item = detail_pesanan::findOrFail($id);
        $item->jumlah += 1;
        $item->jumlah_harga = $item->jumlah * $item->barang->harga;
        $item->save();

        $keranjang = $item->pesanan;
        $keranjang->jumlah_harga = $keranjang->detail->sum('jumlah_harga');
        $keranjang->save();

        return back()->with('success', 'Jumlah ditambah');
    }

    public function kurang($id)
    {
        $item = detail_pesanan::findOrFail($id);

        if ($item->jumlah > 1) {
            $item->jumlah -= 1;
            $item->jumlah_harga = $item->jumlah * $item->barang->harga;
            $item->save();
        } else {
            $item->delete();
        }

        $keranjang = $item->pesanan;
        $keranjang->jumlah_harga = $keranjang->detail->sum('jumlah_harga');
        $keranjang->save();

        return back()->with('success', 'Jumlah dikurangi');
    }

    public function checkout()
    {
        $pesanan = pesanan::where('user_id', Auth::id())
            ->where('status', 0)
            ->first();

        $pesanan_details = $pesanan
            ? $pesanan->detail()->with('barang')->get()
            : collect();

        return view('customer.chart', compact('pesanan', 'pesanan_details'));
    }

    public function hapus($id)
    {
        $detail = detail_pesanan::findOrFail($id);
        $pesanan = pesanan::findOrFail($detail->pesanan_id);

        $pesanan->jumlah_harga -= $detail->jumlah_harga;

        if ($pesanan->jumlah_harga < 0) {
            $pesanan->jumlah_harga = 0;
        }

        $pesanan->save();
        $detail->delete();

        return redirect()->route('customer.checkout')
            ->with('success', 'Barang berhasil dihapus dari keranjang');
    }

    private function midtransConfig()
{
    Config::$serverKey    = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized  = config('midtrans.is_sanitized');
    Config::$is3ds        = config('midtrans.is_3ds');
}

public function bayar(Request $request)
{
    $request->validate([
        'nama_penerima' => 'required|string',
        'no_telepon'    => 'required|string',
        'alamat'        => 'required|string',
        'kota'          => 'required|string',
        'kode_pos'      => 'required|string',
        'catatan'       => 'nullable|string',
    ]);

    $pesanan = pesanan::with('detail.barang')
        ->where('user_id', Auth::id())
        ->where('status', 0)
        ->first();

    if (!$pesanan) {
        return response()->json([
            'message' => 'Tidak ada pesanan untuk dibayar'
        ], 404);
    }

    if ($pesanan->detail->count() < 1) {
        return response()->json([
            'message' => 'Keranjang masih kosong'
        ], 422);
    }

    foreach ($pesanan->detail as $item) {
        if ($item->barang->stok < $item->jumlah) {
            return response()->json([
                'message' => 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi'
            ], 422);
        }
    }

    $total = $pesanan->detail->sum('jumlah_harga');

    $kodeMidtrans = $pesanan->kode;

    if (!$kodeMidtrans) {
        $kodeMidtrans = 'ORD-' . $pesanan->id . '-' . time();
    }

    $pesanan->update([
        'kode'              => $kodeMidtrans,
        'jumlah_harga'      => $total,
        'status'            => 1,
        'nama_penerima'     => $request->nama_penerima,
        'no_telepon'        => $request->no_telepon,
        'alamat'            => $request->alamat,
        'kota'              => $request->kota,
        'kode_pos'          => $request->kode_pos,
        'catatan'           => $request->catatan,
        'payment_status'    => 'pending',
    ]);

    $this->midtransConfig();

    $itemDetails = [];

    foreach ($pesanan->detail as $item) {
        $itemDetails[] = [
            'id'       => $item->barang->id,
            'price'    => (int) $item->barang->harga,
            'quantity' => (int) $item->jumlah,
            'name'     => $item->barang->nama_barang,
        ];
    }

    $params = [
    'transaction_details' => [
        'order_id'     => $kodeMidtrans,
        'gross_amount' => (int) $total,
    ],

    'enabled_payments' => [
        'bank_transfer',
        'gopay',
        'qris',
    ],

    'customer_details' => [
        'first_name' => $request->nama_penerima,
        'phone'      => $request->no_telepon,
        'shipping_address' => [
            'first_name'   => $request->nama_penerima,
            'phone'        => $request->no_telepon,
            'address'      => $request->alamat,
            'city'         => $request->kota,
            'postal_code'  => $request->kode_pos,
            'country_code' => 'IDN',
        ],
    ],

    'item_details' => $itemDetails,
];

    $snapToken = Snap::getSnapToken($params);

    $pesanan->update([
        'snap_token' => $snapToken,
    ]);

    return response()->json([
    'snap_token' => $snapToken,
    'kode'       => $pesanan->kode,
]);
}
public function midtransCallback(Request $request)
{
    $this->midtransConfig();

    $notification = new Notification();

    $orderId = $notification->order_id;
    $transactionStatus = $notification->transaction_status;
    $fraudStatus = $notification->fraud_status ?? null;
    $paymentType = $notification->payment_type ?? null;
    $transactionId = $notification->transaction_id ?? null;

    $pesanan = pesanan::with('detail.barang')
        ->where('kode', $orderId)
        ->first();

    if (!$pesanan) {
        return response()->json([
            'message' => 'Pesanan tidak ditemukan'
        ], 404);
    }

    if ($transactionStatus == 'capture') {
        if ($fraudStatus == 'accept') {
            $this->setPaid($pesanan, $paymentType, $transactionId);
        }
    } elseif ($transactionStatus == 'settlement') {
        $this->setPaid($pesanan, $paymentType, $transactionId);
    } elseif ($transactionStatus == 'pending') {
        $pesanan->update([
            'payment_status'    => 'pending',
            'metode_pembayaran' => $paymentType,
            'transaction_id'    => $transactionId,
        ]);
    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
        $pesanan->update([
            'payment_status'    => 'failed',
            'metode_pembayaran' => $paymentType,
            'transaction_id'    => $transactionId,
        ]);
    }

    return response()->json([
        'message' => 'Callback berhasil diproses'
    ]);
}

private function setPaid($pesanan, $paymentType = null, $transactionId = null)
{
    $pesanan->refresh();

    if ($pesanan->payment_status == 'paid') {
        return;
    }

    DB::transaction(function () use ($pesanan, $paymentType, $transactionId) {

        $pesanan = pesanan::with('detail')
            ->lockForUpdate()
            ->findOrFail($pesanan->id);

        if ($pesanan->payment_status == 'paid') {
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
            'status'            => 1,
            'payment_status'    => 'paid',
            'metode_pembayaran' => $paymentType,
            'transaction_id'    => $transactionId,
            'paid_at'           => now(),
        ]);
    });
}
public function finishPayment(Request $request)
{
    $request->validate([
        'order_id'       => 'required|string',
        'payment_type'   => 'nullable|string',
        'transaction_id' => 'nullable|string',
    ]);

    $pesanan = pesanan::with('detail.barang')
        ->where('kode', $request->order_id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $this->setPaid(
        $pesanan,
        $request->payment_type ?? 'midtrans',
        $request->transaction_id
    );

    return response()->json([
        'message' => 'Pembayaran selesai. Pesanan akan diproses.'
    ]);
}
}