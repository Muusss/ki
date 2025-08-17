<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Makanan
            [
                'kode_menu' => 'MKN001',
                'nama_menu' => 'Nasi Goreng Special',
                'jenis_menu' => 'makanan',
                'harga' => '>20000-<=25000',
                'gambar' => 'nasi-goreng-special.jpg'
            ],
            [
                'kode_menu' => 'MKN002',
                'nama_menu' => 'Ayam Bakar Madu',
                'jenis_menu' => 'makanan',
                'harga' => '>25000-<=30000',
                'gambar' => 'ayam-bakar-madu.jpg'
            ],
            [
                'kode_menu' => 'MKN003',
                'nama_menu' => 'Mie Ayam Bakso',
                'jenis_menu' => 'makanan',
                'harga' => '<=20000',
                'gambar' => 'mie-ayam-bakso.jpg'
            ],
            [
                'kode_menu' => 'MKN004',
                'nama_menu' => 'Steak Sirloin',
                'jenis_menu' => 'makanan',
                'harga' => '>30000',
                'gambar' => 'steak-sirloin.jpg'
            ],
            [
                'kode_menu' => 'MKN005',
                'nama_menu' => 'Spaghetti Carbonara',
                'jenis_menu' => 'makanan',
                'harga' => '>25000-<=30000',
                'gambar' => 'spaghetti-carbonara.jpg'
            ],

            // Cemilan
            [
                'kode_menu' => 'CML001',
                'nama_menu' => 'French Fries',
                'jenis_menu' => 'cemilan',
                'harga' => '<=20000',
                'gambar' => 'french-fries.jpg'
            ],
            [
                'kode_menu' => 'CML002',
                'nama_menu' => 'Onion Rings',
                'jenis_menu' => 'cemilan',
                'harga' => '<=20000',
                'gambar' => 'onion-rings.jpg'
            ],
            [
                'kode_menu' => 'CML003',
                'nama_menu' => 'Chicken Wings BBQ',
                'jenis_menu' => 'cemilan',
                'harga' => '>20000-<=25000',
                'gambar' => 'chicken-wings.jpg'
            ],
            [
                'kode_menu' => 'CML004',
                'nama_menu' => 'Nachos Cheese',
                'jenis_menu' => 'cemilan',
                'harga' => '<=20000',
                'gambar' => 'nachos-cheese.jpg'
            ],

            // Coffee
            [
                'kode_menu' => 'COF001',
                'nama_menu' => 'Espresso',
                'jenis_menu' => 'coffee',
                'harga' => '<=20000',
                'gambar' => 'espresso.jpg'
            ],
            [
                'kode_menu' => 'COF002',
                'nama_menu' => 'Cappuccino',
                'jenis_menu' => 'coffee',
                'harga' => '<=20000',
                'gambar' => 'cappuccino.jpg'
            ],
            [
                'kode_menu' => 'COF003',
                'nama_menu' => 'Caramel Macchiato',
                'jenis_menu' => 'coffee',
                'harga' => '>20000-<=25000',
                'gambar' => 'caramel-macchiato.jpg'
            ],
            [
                'kode_menu' => 'COF004',
                'nama_menu' => 'Vietnamese Drip Coffee',
                'jenis_menu' => 'coffee',
                'harga' => '<=20000',
                'gambar' => 'vietnamese-coffee.jpg'
            ],
            [
                'kode_menu' => 'COF005',
                'nama_menu' => 'Affogato',
                'jenis_menu' => 'coffee',
                'harga' => '>20000-<=25000',
                'gambar' => 'affogato.jpg'
            ],

            // Milkshake
            [
                'kode_menu' => 'MLK001',
                'nama_menu' => 'Chocolate Milkshake',
                'jenis_menu' => 'milkshake',
                'harga' => '>20000-<=25000',
                'gambar' => 'chocolate-milkshake.jpg'
            ],
            [
                'kode_menu' => 'MLK002',
                'nama_menu' => 'Strawberry Milkshake',
                'jenis_menu' => 'milkshake',
                'harga' => '>20000-<=25000',
                'gambar' => 'strawberry-milkshake.jpg'
            ],
            [
                'kode_menu' => 'MLK003',
                'nama_menu' => 'Oreo Milkshake',
                'jenis_menu' => 'milkshake',
                'harga' => '>20000-<=25000',
                'gambar' => 'oreo-milkshake.jpg'
            ],
            [
                'kode_menu' => 'MLK004',
                'nama_menu' => 'Vanilla Milkshake',
                'jenis_menu' => 'milkshake',
                'harga' => '<=20000',
                'gambar' => 'vanilla-milkshake.jpg'
            ],

            // Mojito
            [
                'kode_menu' => 'MJT001',
                'nama_menu' => 'Classic Mojito',
                'jenis_menu' => 'mojito',
                'harga' => '>20000-<=25000',
                'gambar' => 'classic-mojito.jpg'
            ],
            [
                'kode_menu' => 'MJT002',
                'nama_menu' => 'Strawberry Mojito',
                'jenis_menu' => 'mojito',
                'harga' => '>20000-<=25000',
                'gambar' => 'strawberry-mojito.jpg'
            ],
            [
                'kode_menu' => 'MJT003',
                'nama_menu' => 'Watermelon Mojito',
                'jenis_menu' => 'mojito',
                'harga' => '>25000-<=30000',
                'gambar' => 'watermelon-mojito.jpg'
            ],
            [
                'kode_menu' => 'MJT004',
                'nama_menu' => 'Passion Fruit Mojito',
                'jenis_menu' => 'mojito',
                'harga' => '>25000-<=30000',
                'gambar' => 'passionfruit-mojito.jpg'
            ],

            // Yakult
            [
                'kode_menu' => 'YKT001',
                'nama_menu' => 'Original Yakult Float',
                'jenis_menu' => 'yakult',
                'harga' => '<=20000',
                'gambar' => 'yakult-original.jpg'
            ],
            [
                'kode_menu' => 'YKT002',
                'nama_menu' => 'Yakult Lychee',
                'jenis_menu' => 'yakult',
                'harga' => '<=20000',
                'gambar' => 'yakult-lychee.jpg'
            ],
            [
                'kode_menu' => 'YKT003',
                'nama_menu' => 'Yakult Strawberry',
                'jenis_menu' => 'yakult',
                'harga' => '<=20000',
                'gambar' => 'yakult-strawberry.jpg'
            ],
            [
                'kode_menu' => 'YKT004',
                'nama_menu' => 'Yakult Green Tea',
                'jenis_menu' => 'yakult',
                'harga' => '>20000-<=25000',
                'gambar' => 'yakult-greentea.jpg'
            ],

            // Tea
            [
                'kode_menu' => 'TEA001',
                'nama_menu' => 'Thai Tea',
                'jenis_menu' => 'tea',
                'harga' => '<=20000',
                'gambar' => 'thai-tea.jpg'
            ],
            [
                'kode_menu' => 'TEA002',
                'nama_menu' => 'Matcha Latte',
                'jenis_menu' => 'tea',
                'harga' => '>20000-<=25000',
                'gambar' => 'matcha-latte.jpg'
            ],
            [
                'kode_menu' => 'TEA003',
                'nama_menu' => 'Chamomile Tea',
                'jenis_menu' => 'tea',
                'harga' => '<=20000',
                'gambar' => 'chamomile-tea.jpg'
            ],
            [
                'kode_menu' => 'TEA004',
                'nama_menu' => 'Earl Grey Tea',
                'jenis_menu' => 'tea',
                'harga' => '<=20000',
                'gambar' => 'earl-grey.jpg'
            ],
            [
                'kode_menu' => 'TEA005',
                'nama_menu' => 'Jasmine Tea',
                'jenis_menu' => 'tea',
                'harga' => '<=20000',
                'gambar' => 'jasmine-tea.jpg'
            ]
        ];

        foreach ($menus as $menu) {
            Alternatif::updateOrCreate(
                ['kode_menu' => $menu['kode_menu']], 
                $menu
            );
        }
        $this->command->info('✅ Seeder menu berhasil dijalankan!');
        $this->command->info('📊 Total menu yang ditambahkan: ' . count($menus));
        
        // Tampilkan ringkasan
        $this->command->table(
            ['Jenis Menu', 'Jumlah'],
            [
                ['Makanan', Alternatif::where('jenis_menu', 'makanan')->count()],
                ['Cemilan', Alternatif::where('jenis_menu', 'cemilan')->count()],
                ['Coffee', Alternatif::where('jenis_menu', 'coffee')->count()],
                ['Milkshake', Alternatif::where('jenis_menu', 'milkshake')->count()],
                ['Mojito', Alternatif::where('jenis_menu', 'mojito')->count()],
                ['Yakult', Alternatif::where('jenis_menu', 'yakult')->count()],
                ['Tea', Alternatif::where('jenis_menu', 'tea')->count()],
            ]
        );
    }
}