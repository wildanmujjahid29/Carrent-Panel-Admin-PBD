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
        Schema::table('mobils', function (Blueprint $table) {
            $table->string('harga', 20)->after('status'); // Menambahkan kolom harga setelah kolom status
        });
    }
    
    public function down(): void
    {
        Schema::table('mobils', function (Blueprint $table) {
            $table->dropColumn('harga'); // Menghapus kolom harga jika migrasi di-rollback
        });
    }
    
};
