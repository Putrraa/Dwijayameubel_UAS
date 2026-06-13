<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirPesananApiController extends Controller
{
    public function index()
    {
        try {
            $data = DB::table('pesanan')
                ->where('status', '>=', 1)
                ->orderByDesc('tanggal')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode,
                        'tanggal' => $item->tanggal
                            ? date('Y-m-d H:i:s', strtotime($item->tanggal))
                            : null,

                        'nama_penerima' => $item->nama_penerima,
                        'no_telepon' => $item->no_telepon,
                        'alamat' => $item->alamat,
                        'kota' => $item->kota,
                        'kode_pos' => $item->kode_pos,

                        'jumlah_harga' => (int) ($item->jumlah_harga ?? 0),
                        'total' => 'Rp ' . number_format($item->jumlah_harga ?? 0, 0, ',', '.'),

                        'metode_pembayaran' => $item->metode_pembayaran,
                        'metode_label' => $this->formatMetodeBayar($item->metode_pembayaran),

                        'status' => (int) $item->status,
                        'status_label' => $this->statusLabel((int) $item->status),
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Data pesanan berhasil diambil',
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

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:1,2,3',
            ]);

            $pesanan = DB::table('pesanan')->where('id', $id)->first();

            if (!$pesanan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            DB::table('pesanan')
                ->where('id', $id)
                ->update([
                    'status' => (int) $request->status,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'status' => true,
                'message' => 'Status pesanan berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function statusLabel($status)
    {
        return match ((int) $status) {
            1 => 'Diproses',
            2 => 'Dikirim',
            3 => 'Selesai',
            default => 'Diproses',
        };
    }

    private function formatMetodeBayar($metode)
    {
        if (!$metode) {
            return 'BELUM ADA';
        }

        return strtoupper(str_replace('_', ' ', $metode));
    }
}