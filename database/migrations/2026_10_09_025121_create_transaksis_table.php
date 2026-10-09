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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->enum('jenis', ['masuk', 'penjualan', 'transfer']);
            $table->date('tanggal');
            $table->foreignId('gudang_id')->constrained();
            $table->foreignId('gudang_tujuan_id')->nullable()->constrained('gudangs');
            $table->foreignId('pelanggan_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
