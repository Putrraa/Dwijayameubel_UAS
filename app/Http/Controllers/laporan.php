<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\CustomOrder; 

class laporan extends Controller
{
    public function produk()
    {
        return view('produk.index');
    }

    public function kategori()
    {
        return view('kategori.index');
    }
    private function getDataLaporan()
    {
        $pesanan_biasa = pesanan::with('user')
            ->where('status', '>=', 1)
            ->get()
            ->map(function ($item) {
                $item->jenis_pesanan = 'Reguler';
                $item->tanggal_laporan = $item->tanggal;
                return $item;
            });

        $custom_order = CustomOrder::with('user')
            ->whereIn('status', [2, 3, 4])
            ->where('estimasi_harga', '>', 0)
            ->get()
            ->map(function ($item) {
                $item->jenis_pesanan = 'Custom Order';
                $item->kode = 'CSTM-' . $item->id;
                $item->tanggal = $item->created_at;
                $item->tanggal_laporan = $item->created_at;
                return $item;
            });

        return $pesanan_biasa
            ->concat($custom_order)
            ->sortByDesc('tanggal_laporan')
            ->values();
    }

    public function index()
    {
        $data = $this->getDataLaporan();

        return view('barang.laporan', compact('data'));
    }

    public function exportExcel()
{
    $data = $this->getDataLaporan();

    $fileName = 'laporan-pesanan-' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$fileName\"",
    ];

    return response()->stream(function () use ($data) {
        $handle = fopen('php://output', 'w');

        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($handle, [
            'No',
            'Jenis Pesanan',
            'Kode Pesanan',
            'Tanggal',
            'Pembeli',
            'Total Harga',
        ], ';');

        foreach ($data as $index => $item) {
            $totalHarga = $item->jenis_pesanan == 'Reguler'
                ? $item->jumlah_harga
                : $item->estimasi_harga;

            fputcsv($handle, [
                $index + 1,
                $item->jenis_pesanan,
                $item->kode,
                \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i'),
                $item->user->name ?? '-',
                $totalHarga,
            ], ';');
        }

        fclose($handle);
    }, 200, $headers);
}
}