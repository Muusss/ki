<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // helper: tambahkan subkriteria per KODE kriteria
        $add = function (string $kode, array $items, bool $replace = false) {
            $kriteriaId = Kriteria::where('kode', $kode)->value('id');
            if (!$kriteriaId) return;

            if ($replace) {
                SubKriteria::where('kriteria_id', $kriteriaId)->delete();
            }

            foreach ($items as $it) {
                SubKriteria::updateOrCreate(
                    ['kriteria_id' => $kriteriaId, 'label' => $it['label']],
                    [
                        'skor'    => $it['skor'],
                        'min_val' => $it['min_val'] ?? null,
                        'max_val' => $it['max_val'] ?? null,
                    ]
                );
            }
        };

        /**
         * C1: Rasa (benefit)
         */
        $add('C1', [
            ['label' => 'Tidak Enak',    'skor' => 1],
            ['label' => 'Cukup Enak',    'skor' => 2],
            ['label' => 'Enak',          'skor' => 3],
            ['label' => 'Sangat Enak',   'skor' => 4],
        ], true);

        /**
         * C2: Harga (cost) sesuai Excel
         */
        $add('C2', [
            ['label' => '>30.000',                  'skor' => 1, 'min_val' => 30001,  'max_val' => null],
            ['label' => '>25.000 – <=30.000',       'skor' => 2, 'min_val' => 25001,  'max_val' => 30000],
            ['label' => '>20.000 – <=25.000',       'skor' => 3, 'min_val' => 20001,  'max_val' => 25000],
            ['label' => '<=20.000',                 'skor' => 4, 'min_val' => 0,      'max_val' => 20000],
        ], true);

        /**
         * C3: Popularitas (benefit)
         */
        $add('C3', [
            ['label' => 'Kurang',          'skor' => 1],
            ['label' => 'Cukup',           'skor' => 2],
            ['label' => 'Populer',         'skor' => 3],
            ['label' => 'Sangat Populer',  'skor' => 4],
        ], true);

        /**
         * C4: Porsi (benefit)
         */
        $add('C4', [
            ['label' => 'Sedikit', 'skor' => 1],
            ['label' => 'Cukup',   'skor' => 2],
            ['label' => 'Banyak',  'skor' => 3],
            ['label' => 'Sangat Banyak', 'skor' => 4],
        ], true);

        /**
         * C5: Penyajian (benefit)
         */
        $add('C5', [
            ['label' => 'Kurang',          'skor' => 1],
            ['label' => 'Cukup',           'skor' => 2],
            ['label' => 'Menarik',         'skor' => 3],
            ['label' => 'Sangat Menarik',  'skor' => 4],
        ], true);
    }
}
