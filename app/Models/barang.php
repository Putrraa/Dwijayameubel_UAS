<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class barang extends Model
{
    use HasFactory;
    
    protected $table = 'barang';
    protected $primaryKey = 'id';
    
    // 1. Update fillable: ganti 'bahan' menjadi 'bahan_id'
    protected $fillable = [
        'nama_barang',
        'kategori_id', 
        'bahan_id', // Tambahkan ini
        'harga',
        'ukuran',
        'stok',
        'gambar',
        'deskripsi'
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // 2. TAMBAHKAN INI: Relasi ke model Bahan agar error "RelationNotFoundException" hilang
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function detail_pesanan() 
    {
        return $this->hasMany(detail_pesanan::class, 'barang_id');
    }

    public function detail()
    {
        return $this->hasMany(detail_pesanan::class, 'barang_id');
    }
}