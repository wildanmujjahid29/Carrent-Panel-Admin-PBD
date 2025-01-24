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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_customer', 10)->unique();
            $table->string('nama_customer', 50);
            $table->string('alamat', 50);
            $table->string('rt');
            $table->string('rw');
            $table->string('desa', 50);
            $table->string('kecamatan', 50);
            $table->string('kota', 50);
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_hp', 15);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
