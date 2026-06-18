<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $fillable = [

        'user_id',

        'jenis_furniture',

        'jenis_kayu',

        'gambar',

        'ukuran',

        'catatan',

        'nama_penerima',

        'no_telepon',

        'alamat',

        'kota',

        'kode_pos',

        'estimasi_harga',

        'status',

        // MIDTRANS
        'snap_token',

        'payment_status',

        'metode_pembayaran',

        'transaction_id',

        'midtrans_order_id'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
