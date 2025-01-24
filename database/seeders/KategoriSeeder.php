<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'kode_kategori' => 'KTG001',
                'nama_kategori' => 'SUV',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'KTG002',
                'nama_kategori' => 'Sedan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'KTG003',
                'nama_kategori' => 'Hatchback',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kategori' => 'KTG004',
                'nama_kategori' => 'Pickup',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
