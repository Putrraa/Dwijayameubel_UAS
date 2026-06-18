<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KasirCustomOrderApiController extends Controller
{
    public function index()
    {
        try {
            $data = CustomOrder::with('user')
                ->latest()
                ->get()
                ->map(function ($item) {
                    $status = $this->normalizeStatus($item->status);

                    return [
                        'id' => $item->id,
                        'customer' => $item->user->name ?? '-',
                        'jenis_furniture' => $item->jenis_furniture,
                        'jenis_kayu' => $item->jenis_kayu,
                        'ukuran' => $item->ukuran,
                        'catatan' => $item->catatan,
                        'nama_penerima' => $item->nama_penerima,
                        'no_telepon' => $item->no_telepon,
                        'alamat' => $item->alamat,
                        'kota' => $item->kota,
                        'kode_pos' => $item->kode_pos,

                        'gambar_url' => $item->gambar
                            ? asset('storage/' . str_replace(' ', '%20', $item->gambar))
                            : null,

                        'estimasi_harga' => $item->estimasi_harga
                            ? (int) $item->estimasi_harga
                            : null,

                        'harga' => $item->estimasi_harga
                            ? 'Rp ' . number_format($item->estimasi_harga, 0, ',', '.')
                            : '-',

                        'status' => $status,
                        'status_label' => $this->statusLabel($status),
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Data custom order berhasil diambil',
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

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'estimasi_harga' => 'required|numeric|min:0',
                'status' => ['required', Rule::in(['pending', 'diproses', 'selesai'])],
            ]);

            $customOrder = CustomOrder::find($id);

            if (!$customOrder) {
                return response()->json([
                    'status' => false,
                    'message' => 'Custom order tidak ditemukan'
                ], 404);
            }

            $customOrder->estimasi_harga = $request->estimasi_harga;
            $customOrder->status = $request->status;
            $customOrder->save();

            return response()->json([
                'status' => true,
                'message' => 'Custom order berhasil diperbarui'
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

    private function normalizeStatus($status)
    {
        if ($status === 1 || $status === '1') {
            return 'pending';
        }

        if ($status === 2 || $status === '2') {
            return 'diproses';
        }

        if ($status === 3 || $status === '3') {
            return 'selesai';
        }

        if (in_array($status, ['pending', 'diproses', 'selesai'])) {
            return $status;
        }

        return 'pending';
    }

    private function statusLabel($status)
    {
        return match ($status) {
            'pending' => 'Menunggu',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            default => 'Menunggu',
        };
    }
}
