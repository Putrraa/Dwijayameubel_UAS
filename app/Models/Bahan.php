<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahans'; // Pastikan nama tabel di DB sesuai (biasanya plural)
    protected $fillable = ['nama_bahan'];

    /**
     * Relasi: Satu bahan bisa dimiliki oleh banyak barang
     */
    public function barang()
    {
        return $this->hasMany(barang::class, 'bahan_id');
    }
}