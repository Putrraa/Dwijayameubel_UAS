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
    try {
        $data = barang::with(['kategori', 'bahan'])
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'kategori_id' => $item->kategori_id,
                    'bahan_id' => $item->bahan_id,
                    'harga' => (string) $item->harga,
                    'stok' => (int) $item->stok,
                    'ukuran' => $item->ukuran,
                    'deskripsi' => $item->deskripsi,
                    'gambar' => $item->gambar,

                    'gambar_url' => $item->gambar
                        ? asset('storage/barang/' . str_replace(' ', '%20', $item->gambar))
                        : null,

                    'kategori' => $item->kategori ? [
                        'id' => $item->kategori->id,
                        'nama_kategori' => $item->kategori->nama_kategori,
                        'gambar' => $item->kategori->gambar ?? null,
                        'gambar_url' => isset($item->kategori->gambar) && $item->kategori->gambar
                            ? asset('storage/' . str_replace(' ', '%20', $item->kategori->gambar))
                            : null,
                    ] : null,

                    'bahan' => $item->bahan ? [
                        'id' => $item->bahan->id,
                        'nama_bahan' => $item->bahan->nama_bahan,
                    ] : null,
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diambil',
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
        try {
            $data = Kategori::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama_kategori' => $item->nama_kategori,
                    'gambar' => $item->gambar ?? null,
                    'gambar_url' => $item->gambar
                        ? asset('storage/' . str_replace(' ', '%20', $item->gambar))
                        : null,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil diambil',
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
        try {
            $item = barang::with(['kategori', 'bahan'])->find($id);

            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'Barang tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $data = [
                'id' => $item->id,
                'nama_barang' => $item->nama_barang,
                'kategori_id' => $item->kategori_id,
                'bahan_id' => $item->bahan_id,
                'harga' => (string) $item->harga,
                'stok' => (int) $item->stok,
                'ukuran' => $item->ukuran,
                'deskripsi' => $item->deskripsi,
                'gambar' => $item->gambar,

                'gambar_url' => $item->gambar
                    ? asset('storage/barang/' . str_replace(' ', '%20', $item->gambar))
                    : null,

                'kategori' => $item->kategori ? [
                    'id' => $item->kategori->id,
                    'nama_kategori' => $item->kategori->nama_kategori,
                    'gambar' => $item->kategori->gambar ?? null,
                    'gambar_url' => isset($item->kategori->gambar) && $item->kategori->gambar
                        ? asset('storage/' . str_replace(' ', '%20', $item->kategori->gambar))
                        : null,
                ] : null,

                'bahan' => $item->bahan ? [
                    'id' => $item->bahan->id,
                    'nama_bahan' => $item->bahan->nama_bahan,
                ] : null,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Detail barang berhasil diambil',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function storeKategori(Request $request)
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.unique' => 'Kategori sudah ada',
                'gambar.image' => 'File harus berupa gambar',
                'gambar.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp',
                'gambar.max' => 'Ukuran gambar maksimal 2MB',
            ]);

            $gambarPath = null;

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaFile = time() . '_' . $file->getClientOriginalName();

                $file->storeAs('kategori', $namaFile, 'public');

                // Disimpan dengan folder agar cocok dengan asset('storage/' . $item->gambar)
                $gambarPath = 'kategori/' . $namaFile;
            }

            Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'gambar' => $gambarPath,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil ditambahkan'
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

    public function storeBahan(Request $request)
    {
        try {
            $request->validate([
                'nama_bahan' => 'required|string|max:255|unique:bahans,nama_bahan',
            ], [
                'nama_bahan.required' => 'Nama bahan wajib diisi',
                'nama_bahan.unique' => 'Bahan sudah ada',
            ]);

            Bahan::create([
                'nama_bahan' => $request->nama_bahan,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Bahan berhasil ditambahkan'
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
}