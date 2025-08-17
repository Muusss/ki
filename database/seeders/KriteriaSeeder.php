<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Kriteria dari Excel (Sheet3)
        $kriterias = [
            [
                'kode' => 'C1',
                'kriteria' => 'Rasa',
                'atribut' => 'benefit',
                'urutan_prioritas' => 1,
            ],
            [
                'kode' => 'C2',
                'kriteria' => 'Harga',
                'atribut' => 'cost',
                'urutan_prioritas' => 2,
            ],
            [
                'kode' => 'C3',
                'kriteria' => 'Popularitas',
                'atribut' => 'benefit',
                'urutan_prioritas' => 3,
            ],
            [
                'kode' => 'C4',
                'kriteria' => 'Porsi',
                'atribut' => 'benefit',
                'urutan_prioritas' => 4,
            ],
            [
                'kode' => 'C5',
                'kriteria' => 'Penyajian',
                'atribut' => 'benefit',
                'urutan_prioritas' => 5,
            ],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['kode' => $kriteria['kode']],
                $kriteria
            );
        }

        // Hitung bobot ROC sesuai urutan prioritas
        Kriteria::hitungROC();
    }
}
