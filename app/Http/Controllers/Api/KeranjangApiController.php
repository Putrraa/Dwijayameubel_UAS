<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\pesanan;
use App\Models\detail_pesanan;
use Illuminate\Http\Request;

class KeranjangApiController extends Controller
{
    public function index($userId)
    {
        $pesanan = pesanan::where('user_id', $userId)
            ->where('status', 0)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => true,
                'message' => 'Keranjang kosong',
                'data' => []
            ]);
        }

        $data = detail_pesanan::with('barang')
            ->where('pesanan_id', $pesanan->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'barang_id' => $item->barang_id,
                    'nama_barang' => $item->barang ? $item->barang->nama_barang : '-',
                    'harga' => $item->barang ? (string) $item->barang->harga : '0',
                    'jumlah' => $item->jumlah,
                    'subtotal' => (string) $item->jumlah_harga,
                    'gambar_url' => $item->barang && $item->barang->gambar
                        ? asset('storage/' . $item->barang->gambar)
                        : null,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Data keranjang berhasil diambil',
            'data' => $data
        ]);
    }

    public function updateJumlah(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $detail = detail_pesanan::with('barang')->find($id);

        if (!$detail) {
            return response()->json([
                'status' => false,
                'message' => 'Item keranjang tidak ditemukan'
            ]);
        }

        $harga = $detail->barang ? $detail->barang->harga : 0;

        $detail->update([
            'jumlah' => $request->jumlah,
            'jumlah_harga' => $harga * $request->jumlah
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Jumlah berhasil diperbarui'
        ]);
    }

    public function hapus($id)
    {
        $detail = detail_pesanan::find($id);

        if (!$detail) {
            return response()->json([
                'status' => false,
                'message' => 'Item tidak ditemukan'
            ]);
        }

        $detail->delete();

        return response()->json([
            'status' => true,
            'message' => 'Item berhasil dihapus'
        ]);
    }

    public function bayar($userId)
    {
        $pesanan = pesanan::where('user_id', $userId)
            ->where('status', 0)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status' => false,
                'message' => 'Keranjang kosong'
            ]);
        }

        $total = detail_pesanan::where('pesanan_id', $pesanan->id)
            ->sum('jumlah_harga');

        $pesanan->update([
            'status' => 1,
            'jumlah_harga' => $total
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil diproses'
        ]);
    }
}