<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;

class CustomOrderController extends Controller
{
    // DASHBOARD KASIR
    public function index()
    {
        $data = CustomOrder::latest()->get();

        return view('kasir.index', compact('data'));
    }

    // CUSTOMER REQUEST CUSTOM
    public function store(Request $request)
{
    $request->validate([
        'jenis_furniture' => 'required',
        'jenis_kayu' => 'required',
        'ukuran' => 'required',
        'catatan' => 'nullable',
        'nama_penerima' => 'required|string|max:255',
        'no_telepon' => 'required|string|max:255',
        'alamat' => 'required|string',
        'kota' => 'required|string|max:255',
        'kode_pos' => 'required|string|max:20',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $gambarPath = null;

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();

        $tujuan = public_path('storage/custom');

        if (!file_exists($tujuan)) {
            mkdir($tujuan, 0755, true);
        }

        $file->move($tujuan, $namaFile);

        $gambarPath = 'storage/custom/' . $namaFile;
    }

    CustomOrder::create([
        'user_id' => auth()->id(),
        'jenis_furniture' => $request->jenis_furniture,
        'jenis_kayu' => $request->jenis_kayu,
        'gambar' => $gambarPath,
        'ukuran' => $request->ukuran,
        'catatan' => $request->catatan,
        'nama_penerima' => $request->nama_penerima,
        'no_telepon' => $request->no_telepon,
        'alamat' => $request->alamat,
        'kota' => $request->kota,
        'kode_pos' => $request->kode_pos,
        'estimasi_harga' => null,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Custom order berhasil dikirim');
}
    // UPDATE STATUS + HARGA KASIR
    // Kasir hanya boleh set status pending/diproses.
    // Status "selesai" hanya bisa dikonfirmasi oleh customer sendiri
    // lewat halaman riwayat (tombol "Barang Sudah Sampai").
    public function status($id, Request $request)
    {
        $request->validate([

            'estimasi_harga' => 'required|numeric',
            'status' => ['required', \Illuminate\Validation\Rule::in(['pending', 'diproses'])],

        ]);

        $order = CustomOrder::findOrFail($id);

        $order->estimasi_harga = $request->estimasi_harga;

        $order->status = $request->status;

        $order->save();

        return back()->with(
            'success',
            'Harga dan status berhasil diupdate'
        );
    }
    
}
