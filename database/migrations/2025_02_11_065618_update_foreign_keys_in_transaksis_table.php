<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['mobil_id']);
            $table->dropForeign(['customer_id']);

            $table->foreign('mobil_id')->references('id')->on('mobils');
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropForeign(['mobil_id']);
            $table->dropForeign(['customer_id']);

            $table->foreign('mobil_id')->references('id')->on('mobils')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
        });
    }
};

