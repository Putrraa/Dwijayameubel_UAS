<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telepon')->nullable()->after('email');
            $table->text('alamat')->nullable()->after('no_telepon');
            $table->string('kota')->nullable()->after('alamat');
            $table->string('kode_pos')->nullable()->after('kota');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_telepon', 'alamat', 'kota', 'kode_pos']);
        });
    }
};