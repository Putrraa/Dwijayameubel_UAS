<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('kode');
            $table->string('payment_status')->nullable()->after('metode_pembayaran');
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'payment_status',
                'transaction_id',
                'paid_at',
            ]);
        });
    }
};