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
    Schema::table('barang', function (Blueprint $table) {
        // Menambahkan kolom foreign key
        $table->unsignedBigInteger('kategori_id')->nullable()->after('id');
        $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            //
        });
    }
};
