<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class detail_pesanan extends Model
{
    use HasFactory;
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id';
    protected $fillable = ['pesanan_id','barang_id','jumlah','jumlah_harga'];
    public function barang()
    {
        return $this->belongsTo(barang::class, 'barang_id');
    }
    public function pesanan()
    {
        return $this->belongsTo(pesanan::class, 'pesanan_id');
    }
    

}
