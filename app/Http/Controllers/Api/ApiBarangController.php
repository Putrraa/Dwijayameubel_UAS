<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\barang;
use App\Models\Kategori;
use App\Models\Bahan;

class ApiBarangController extends Controller
{
    public function index()
    {
        $data = barang::with(['kategori', 'bahan'])
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->gambar_url = $item->gambar ? asset('storage/barang/' . $item->gambar) : null;
                return $item;
            });

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diambil',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'bahan_id'    => 'required|exists:bahans,id',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'ukuran'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('barang', $namaFile, 'public');
            $gambarPath = $namaFile;
        }

        $barang = new barang();
        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_id = $request->kategori_id;
        $barang->bahan_id    = $request->bahan_id;
        $barang->harga       = $request->harga;
        $barang->stok        = $request->stok;
        $barang->ukuran      = $request->ukuran;
        $barang->deskripsi   = $request->deskripsi;
        $barang->gambar      = $gambarPath;
        $barang->save();

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang,
            'gambar_url' => $gambarPath ? asset('storage/barang/' . $gambarPath) : null
        ]);
    }

    public function update(Request $request, $id)
    {
        $barang = barang::findOrFail($id);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'bahan_id'    => 'required|exists:bahans,id',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'ukuran'      => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar && file_exists(storage_path('app/public/barang/' . $barang->gambar))) {
                unlink(storage_path('app/public/barang/' . $barang->gambar));
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName(); 
            $file->storeAs('barang', $namaFile, 'public');
            $barang->gambar = $namaFile;
        }

        $barang->nama_barang = $request->nama_barang;
        $barang->kategori_id = $request->kategori_id;
        $barang->bahan_id    = $request->bahan_id;
        $barang->harga       = $request->harga;
        $barang->stok        = $request->stok;
        $barang->ukuran      = $request->ukuran;
        $barang->deskripsi   = $request->deskripsi;
        $barang->save();

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang,
            'gambar_url' => $barang->gambar ? asset('storage/barang/' . $barang->gambar) : null
        ]);
    }

    public function destroy($id)
    {
        $barang = barang::findOrFail($id);

        if ($barang->gambar && file_exists(storage_path('app/public/barang/' . $barang->gambar))) {
            unlink(storage_path('app/public/barang/' . $barang->gambar));
        }

        $barang->delete();

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil dihapus'
        ]);
    }

    public function kategori()
{
    $data = Kategori::all()->map(function ($item) {
        $item->gambar_url = $item->gambar
            ? asset('storage/' . $item->gambar)
            : null;
        return $item;
    });

    return response()->json([
        'status' => true,
        'message' => 'Data kategori berhasil diambil',
        'data' => $data
    ]);
}

    public function bahan()
    {
        return response()->json([
            'status' => true,
            'message' => 'Data bahan berhasil diambil',
            'data' => Bahan::all()
        ]);
    }
    public function show($id)
{
    $barang = barang::with(['kategori', 'bahan'])->findOrFail($id);
    $barang->gambar_url = $barang->gambar
        ? asset('storage/barang/' . $barang->gambar)
        : null;

    return response()->json([
        'status' => true,
        'data' => $barang
    ]);
}
}