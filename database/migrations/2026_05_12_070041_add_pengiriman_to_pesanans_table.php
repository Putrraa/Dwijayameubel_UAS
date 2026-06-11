<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('nama_penerima')->nullable()->after('kode');
            $table->string('no_telepon')->nullable()->after('nama_penerima');
            $table->text('alamat')->nullable()->after('no_telepon');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('kode_pos')->nullable()->after('kota');
            $table->string('metode_pembayaran')->nullable()->after('kode_pos');
            $table->text('catatan')->nullable()->after('metode_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'nama_penerima', 'no_telepon', 'alamat',
                'kota', 'kode_pos', 'metode_pembayaran', 'catatan'
            ]);
        });
    }
};
