<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class UpdateProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Update nama gambar untuk produk yang ada
        // Pastikan Anda telah menyimpan gambar dengan nama yang sesuai di public/img/produk/
        
        $products = [
            'PRD001' => 'omg.jpg',        // Simpan gambar sebagai omg.jpg
            'PRD002' => 'wardah.jpg',     // Simpan gambar sebagai wardah.jpg
            'PRD003' => 'azarine.jpg',    // Simpan gambar sebagai azarine.jpg
            'PRD004' => 'emina.jpg',      // Simpan gambar sebagai emina.jpg
            'PRD005' => 'cosrx.jpg',      // Simpan gambar sebagai cosrx.jpg
        ];

        foreach ($products as $kode => $gambar) {
            Alternatif::where('kode_produk', $kode)->update([
                'gambar' => $gambar
            ]);
        }

        $this->command->info('✅ Gambar produk telah diupdate!');
        $this->command->table(
            ['Kode Produk', 'File Gambar'],
            collect($products)->map(function($gambar, $kode) {
                return [$kode, $gambar];
            })
        );
    }
}