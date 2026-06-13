<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\CustomOrder;
use Carbon\Carbon;

class ProfileApiController extends Controller
{
    public function profile($id)
    {
        $user = User::select('id', 'name', 'email', 'role')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Data profile berhasil diambil',
            'data' => $user
        ]);
    }

    public function riwayatPesanan($userId)
    {
        $data = Pesanan::where('user_id', $userId)
            ->where('status', '>=', 1)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_pesanan' => $item->kode ?? '#DWJ-' . $item->id,
                    'tanggal' => $item->tanggal
                        ? Carbon::parse($item->tanggal)->format('d M Y')
                        : '-',
                    'total' => 'Rp ' . number_format($item->jumlah_harga ?? 0, 0, ',', '.'),
                    'status' => $this->formatStatusPesanan($item->status),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Riwayat pesanan berhasil diambil',
            'data' => $data
        ]);
    }

    public function customOrder($userId)
    {
        $data = \App\Models\CustomOrder::where('user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'furniture_nama' => $item->jenis_furniture,
                    'kayu' => $item->jenis_kayu,
                    'ukuran' => $item->ukuran,
                    'harga' => $item->estimasi_harga
                        ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.')
                        : '-',
                    'status' => $this->formatStatusCustom($item->status),

                    // INI YANG PENTING
                    'gambar_url' => $item->gambar
                        ? asset('storage/' . str_replace(' ', '%20', $item->gambar))
                        : null,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Custom order berhasil diambil',
            'data' => $data
        ]);
    }

    private function formatStatusCustom($status)
    {
        return match ((int) $status) {
            1 => 'Pending',
            2 => 'Diproses',
            3 => 'Selesai',
            4 => 'Ditolak',
            default => 'Pending',
        };
    }
}