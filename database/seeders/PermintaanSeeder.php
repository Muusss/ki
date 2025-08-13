<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permintaan;

class PermintaanSeeder extends Seeder
{
    public function run(): void
    {
        $permintaan = [
            [
                'nama_produk' => 'Sunscreen A',
                'komposisi' => 'Titanium Dioxide, Zinc Oxide, Aloe Vera',
                'harga' => '< 50k',
                'spf' => '30'
            ],
            [
                'nama_produk' => 'Sunscreen B',
                'komposisi' => 'Avobenzone, Octinoxate, Vitamin E',
                'harga' => '50-100k',
                'spf' => '40'
            ],
            [
                'nama_produk' => 'Sunscreen C',
                'komposisi' => 'Zinc Oxide, Niacinamide, Hyaluronic Acid',
                'harga' => '> 100k',
                'spf' => '50+'
            ],
        ];

        foreach ($permintaan as $p) {
            Permintaan::create($p);
        }
    }
}