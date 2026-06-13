<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LaporanApiController extends Controller
{
    public function index()
    {
        try {
            $reguler = DB::table('pesanan')
                ->leftJoin('users', 'pesanan.user_id', '=', 'users.id')
                ->where('pesanan.status', '>=', 1)
                ->select(
                    'pesanan.id',
                    'pesanan.kode',
                    'pesanan.tanggal',
                    'users.name as pembeli',
                    'pesanan.jumlah_harga as total_harga'
                )
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jenis_pesanan' => 'Reguler',
                        'kode' => $item->kode ?? 'REG-' . $item->id,
                        'tanggal' => $item->tanggal
                            ? date('Y-m-d H:i:s', strtotime($item->tanggal))
                            : null,
                        'pembeli' => $item->pembeli ?? '-',
                        'total_harga' => (int) ($item->total_harga ?? 0),
                    ];
                });

            $custom = DB::table('custom_orders')
                ->leftJoin('users', 'custom_orders.user_id', '=', 'users.id')
                ->whereNotNull('custom_orders.estimasi_harga')
                ->where('custom_orders.estimasi_harga', '>', 0)
                ->select(
                    'custom_orders.id',
                    'custom_orders.created_at as tanggal',
                    'users.name as pembeli',
                    'custom_orders.estimasi_harga as total_harga'
                )
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jenis_pesanan' => 'Custom Order',
                        'kode' => 'CUST-' . $item->id,
                        'tanggal' => $item->tanggal
                            ? date('Y-m-d H:i:s', strtotime($item->tanggal))
                            : null,
                        'pembeli' => $item->pembeli ?? '-',
                        'total_harga' => (int) ($item->total_harga ?? 0),
                    ];
                });

            $data = $reguler
                ->merge($custom)
                ->sortByDesc('tanggal')
                ->values();

            return response()->json([
                'status' => true,
                'message' => 'Data laporan berhasil diambil',
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
}