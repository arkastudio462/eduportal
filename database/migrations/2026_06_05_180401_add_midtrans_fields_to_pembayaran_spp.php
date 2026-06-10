<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->string('snap_token', 255)->nullable()->after('tanggal_bayar');
            $table->text('payment_link')->nullable()->after('snap_token');
            $table->string('midtrans_transaction_id', 100)->nullable()->after('payment_link');
            $table->string('midtrans_status', 50)->nullable()->after('midtrans_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_spp', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_link', 'midtrans_transaction_id', 'midtrans_status']);
        });
    }
};
