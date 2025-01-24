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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mobil', 10)->unique();
            $table->foreignId('kategori_id')->constrained('kategoris')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_mobil', 50);
            $table->string('merk', 50)->nullable();
            $table->string('warna', 50)->nullable();
            $table->string('tahun', 4)->nullable();
            $table->string('plat_nomor', 10);
            $table->string('status', 10);
            $table->string('gambar', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
