<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangApiController extends Controller
{
    public function index($userId)
    {
        try {
            $pesanan = DB::table('pesanan')
                ->where('user_id', $userId)
                ->where('status', 0)
                ->first();

            if (!$pesanan) {
                return response()->json([
                    'status' => true,
                    'message' => 'Keranjang kosong',
                    'data' => []
                ]);
            }

            $data = DB::table('detail_pesanan')
                ->join('barang', 'detail_pesanan.barang_id', '=', 'barang.id')
                ->where('detail_pesanan.pesanan_id', $pesanan->id)
                ->select(
                    'detail_pesanan.id',
                    'detail_pesanan.barang_id',
                    'barang.nama_barang',
                    'barang.harga',
                    'barang.gambar',
                    'detail_pesanan.jumlah',
                    'detail_pesanan.jumlah_harga'
                )
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'barang_id' => $item->barang_id,
                        'nama_barang' => $item->nama_barang,
                        'harga' => (string) $item->harga,
                        'jumlah' => (int) $item->jumlah,
                        'subtotal' => (string) $item->jumlah_harga,

                        // ini yang dipakai Glide di Android
                        'gambar_url' => $item->gambar
                            ? asset('storage/' . $item->gambar)
                            : null,
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Data keranjang berhasil diambil',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function updateJumlah(Request $request, $id)
    {
        try {
            $request->validate([
                'jumlah' => 'required|integer|min:1'
            ]);

            $detail = DB::table('detail_pesanan')
                ->join('barang', 'detail_pesanan.barang_id', '=', 'barang.id')
                ->where('detail_pesanan.id', $id)
                ->select(
                    'detail_pesanan.id',
                    'detail_pesanan.barang_id',
                    'barang.harga'
                )
                ->first();

            if (!$detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Item keranjang tidak ditemukan'
                ]);
            }

            $subtotal = $detail->harga * $request->jumlah;

            DB::table('detail_pesanan')
                ->where('id', $id)
                ->update([
                    'jumlah' => $request->jumlah,
                    'jumlah_harga' => $subtotal,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Jumlah berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapus($id)
    {
        try {
            $detail = DB::table('detail_pesanan')->where('id', $id)->first();

            if (!$detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'Item tidak ditemukan'
                ]);
            }

            DB::table('detail_pesanan')->where('id', $id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Item berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bayar($userId)
    {
        try {
            $pesanan = DB::table('pesanan')
                ->where('user_id', $userId)
                ->where('status', 0)
                ->first();

            if (!$pesanan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Keranjang kosong'
                ]);
            }

            $total = DB::table('detail_pesanan')
                ->where('pesanan_id', $pesanan->id)
                ->sum('jumlah_harga');

            DB::table('pesanan')
                ->where('id', $pesanan->id)
                ->update([
                    'status' => 1,
                    'jumlah_harga' => $total,
                    'updated_at' => now()
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil diproses'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}