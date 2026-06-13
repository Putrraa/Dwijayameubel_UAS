<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use Illuminate\Http\Request;

class CustomOrderApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis_furniture' => 'required|string|max:255',
            'jenis_kayu' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('custom_orders', 'public');
        }

        CustomOrder::create([
            'user_id' => $request->user_id,
            'jenis_furniture' => $request->jenis_furniture,
            'jenis_kayu' => $request->jenis_kayu,
            'ukuran' => $request->ukuran,
            'catatan' => $request->catatan,
            'gambar' => $gambarPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan custom berhasil dikirim'
        ]);
    }
}