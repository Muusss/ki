<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            ['kode_produk' => 'PRD001', 'nama_produk' => 'omg', 'jenis_kulit' => 'normal'],
            ['kode_produk' => 'PRD002', 'nama_produk' => 'wardah', 'jenis_kulit' => 'berminyak'],
            ['kode_produk' => 'PRD003', 'nama_produk' => 'azarine', 'jenis_kulit' => 'kering'],
            ['kode_produk' => 'PRD004', 'nama_produk' => 'emina', 'jenis_kulit' => 'kombinasi'],
            ['kode_produk' => 'PRD005', 'nama_produk' => 'cosrx', 'jenis_kulit' => 'normal'],
        ];

        foreach ($produk as $p) {
            Alternatif::updateOrCreate(
                ['kode_produk' => $p['kode_produk']], 
                $p
            );
        }
    }
}