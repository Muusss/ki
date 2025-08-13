<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Data kriteria sesuai penelitian
        $kriterias = [
            [
                'kode' => 'C1',
                'kriteria' => 'Kesesuain Jenis Kulit',
                'atribut' => 'benefit',
                'urutan_prioritas' => 1
            ],
            [
                'kode' => 'C2',
                'kriteria' => 'SPF',
                'atribut' => 'benefit',
                'urutan_prioritas' => 2
            ],
            [
                'kode' => 'C3',
                'kriteria' => 'Harga',
                'atribut' => 'cost',
                'urutan_prioritas' => 3
            ],
            [
                'kode' => 'C4',
                'kriteria' => 'Komposisi',
                'atribut' => 'cost',
                'urutan_prioritas' => 4
            ],
            [
                'kode' => 'C5',
                'kriteria' => 'Efek Samping',
                'atribut' => 'cost',
                'urutan_prioritas' => 5
            ],
            [
                'kode' => 'C6',
                'kriteria' => 'Tekstur',
                'atribut' => 'benefit',
                'urutan_prioritas' => 6
            ],
            [
                'kode' => 'C7',
                'kriteria' => 'Ukuran',
                'atribut' => 'cost',
                'urutan_prioritas' => 7
            ]
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['kode' => $kriteria['kode']],
                $kriteria
            );
        }

        // Hitung bobot ROC
        Kriteria::hitungROC();
    }
}