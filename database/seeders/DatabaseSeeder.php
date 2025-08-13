<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Memulai seeding database...');
        
        // Urutan penting - jangan diubah
        $this->call([
            UserSeeder::class,
            KriteriaSeeder::class,
            AlternatifSeeder::class,    
            SubKriteriaSeeder::class,
            PermintaanSeeder::class,
        ]);

        $this->command->info('📊 Menghitung ROC + SMART...');
        
        // Setelah data ada, langsung proses ROC + SMART + Ranking
       // Kriteria::hitungROC();
        //Penilaian::normalisasiSMART();
        //NilaiAkhir::hitungTotal();
        
        $this->command->info('✅ Seeding selesai! Database siap digunakan.');
        
        // Tampilkan informasi login
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@gmail.com', 'admin1234'],
            ]
        );
    }
}