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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->integer('lama_peminjaman')->after('tanggal_sewa')->nullable(); // Lama peminjaman dalam hari
            $table->enum('status', ['sewa', 'kembali'])->default('sewa')->after('total_harga')->nullable(); // Status transaksi
        });
    
        Schema::table('mobils', function (Blueprint $table) {
            $table->string('status', 10)->default('tersedia')->change(); // Update default status mobil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn('lama_peminjaman');
            $table->dropColumn('status');
        });
    
        Schema::table('mobils', function (Blueprint $table) {
            $table->string('status', 10)->default(null)->change();
        });
    }
};
