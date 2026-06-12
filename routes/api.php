<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiBarangController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProfileApiController;

Route::get('/profile/{id}', [ProfileApiController::class, 'profile']);
Route::get('/riwayat-pesanan/{userId}', [ProfileApiController::class, 'riwayatPesanan']);
Route::get('/custom-order/{userId}', [ProfileApiController::class, 'customOrder']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/cek-api', function () {
    return response()->json([
        'status' => true,
        'message' => 'API Laravel berhasil jalan'
    ]);
});

Route::get('/barang', [ApiBarangController::class, 'index']);

// Tambah barang dari APK
Route::post('/barang/store', [ApiBarangController::class, 'store']);

// Update barang dari APK
Route::post('/barang/update/{id}', [ApiBarangController::class, 'update']);

// Hapus barang dari APK
Route::post('/barang/delete/{id}', [ApiBarangController::class, 'destroy']);

Route::get('/barang/{id}', [ApiBarangController::class, 'show']);
// ==============================
// API DATA TAMBAHAN UNTUK FORM APK
// ==============================

// Ambil kategori untuk spinner Android
Route::get('/kategori', [ApiBarangController::class, 'kategori']);

// Ambil bahan untuk spinner Android
Route::get('/bahan', [ApiBarangController::class, 'bahan']);