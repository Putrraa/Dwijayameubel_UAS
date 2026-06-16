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
                $metodePembayaran = $item->metode_pembayaran ?: 'midtrans';

                return [
                    'id' => $item->id,
                    'no_pesanan' => $item->kode ?? '#DWJ-' . $item->id,
                    'tanggal' => $item->tanggal
                        ? Carbon::parse($item->tanggal)->format('d M Y')
                        : '-',
                    'total' => 'Rp ' . number_format($item->jumlah_harga ?? 0, 0, ',', '.'),
                    'status' => $this->formatStatusPesanan($item->status),
                    'payment_status' => $item->payment_status ?: 'pending',
                    'metode_pembayaran' => $metodePembayaran,
                    'metode_label' => $this->formatMetodeBayar($metodePembayaran),
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
                $metodePembayaran = $item->metode_pembayaran ?? 'midtrans';

                return [
                    'id' => $item->id,
                    'furniture_nama' => $item->jenis_furniture,
                    'kayu' => $item->jenis_kayu,
                    'ukuran' => $item->ukuran,
                    'harga' => $item->estimasi_harga
                        ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.')
                        : '-',
                    'status' => $this->formatStatusCustom($item->status),
                    'payment_status' => $item->payment_status ?? 'pending',
                    'metode_pembayaran' => $metodePembayaran,
                    'metode_label' => $this->formatMetodeBayar($metodePembayaran),

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
        return match ($status) {
            'pending', 1 => 'pending',
            'diproses', 2 => 'diproses',
            'selesai', 3 => 'selesai',
            'ditolak', 4 => 'ditolak',
            default => 'pending',
        };
    }

    private function formatStatusPesanan($status)
    {
        return match ((int) $status) {
            1 => 'Diproses',
            2 => 'Dikirim',
            3 => 'Selesai',
            4 => 'Dibatalkan',
            default => 'Pending',
        };
    }

    private function formatMetodeBayar($metode)
    {
        return match ($metode) {
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'bank_transfer' => 'Bank Transfer',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'midtrans' => 'Midtrans',
            default => $metode ? strtoupper(str_replace('_', ' ', $metode)) : 'Midtrans',
        };
    }
}
