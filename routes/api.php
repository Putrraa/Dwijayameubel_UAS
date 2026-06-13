<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiBarangController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\CustomOrderApiController;
use App\Http\Controllers\Api\KeranjangApiController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\Api\PenggunaApiController;
use App\Http\Controllers\Api\KasirCustomOrderApiController;
use App\Http\Controllers\Api\KasirPesananApiController;

Route::get('/kasir/pesanan', [KasirPesananApiController::class, 'index']);
Route::post('/kasir/pesanan/update-status/{id}', [KasirPesananApiController::class, 'updateStatus']);
Route::get('/kasir/custom-order', [KasirCustomOrderApiController::class, 'index']);
Route::post('/kasir/custom-order/update/{id}', [KasirCustomOrderApiController::class, 'update']);
Route::get('/pengguna', [PenggunaApiController::class, 'index']);
Route::post('/pengguna/store', [PenggunaApiController::class, 'store']);
Route::post('/pengguna/update/{id}', [PenggunaApiController::class, 'update']);
Route::post('/pengguna/delete/{id}', [PenggunaApiController::class, 'destroy']);
Route::get('/laporan', [LaporanApiController::class, 'index']);
Route::get('/keranjang/{userId}', [KeranjangApiController::class, 'index']);
Route::post('/keranjang/update/{id}', [KeranjangApiController::class, 'updateJumlah']);
Route::post('/keranjang/delete/{id}', [KeranjangApiController::class, 'hapus']);
Route::post('/keranjang/bayar/{userId}', [KeranjangApiController::class, 'bayar']);
Route::post('/custom-order/store', [CustomOrderApiController::class, 'store']);
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