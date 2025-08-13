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

        $add('C1', [
            ['label'=>'Tidak terdapat Kandungan',       'skor'=>1],
            ['label'=>'Terdapat 1 Kandungan',        'skor'=>2],
            ['label'=>'Terdapat 2 Kandungan',         'skor'=>3],
            ['label'=>'Terdapat >2 Kandungan',  'skor'=>4],
        ], true);

        
        $add('C2', [
            ['label'=>'30',  'skor'=>1, 'min_val'=>30,  'max_val'=>30],
            ['label'=>'35', 'skor'=>2, 'min_val'=>35, 'max_val'=>35],
            ['label'=>'40', 'skor'=>3, 'min_val'=>40, 'max_val'=>40],
            ['label'=>'50+',  'skor'=>4, 'min_val'=>50, 'max_val'=>60],
        ], true);

       
        $add('C3', [
            ['label'=>'>80k',  'skor'=>1, 'min_val'=>80000,  'max_val'=>150000],
            ['label'=>'61-80k', 'skor'=>2, 'min_val'=>61000, 'max_val'=>80000],
            ['label'=>'40k-60k', 'skor'=>3, 'min_val'=>40000, 'max_val'=>60000],
            ['label'=>'<40k',  'skor'=>4, 'min_val'=>10000, 'max_val'=>40000],
        ], true);

        
        $add('C4', [
            ['label'=>'Terdapat Kandungan Paraben dan Fragrance', 'skor'=>1],
            ['label'=>'Terdapat Kandungan Paraben',   'skor'=>2],
            ['label'=>'Terdapat Kandungan Fragrance',   'skor'=>3],
            ['label'=>'Tidak Terdapat Kandungan Paraben dan Fragrance', 'skor'=>4],
        ], true);

        
        $add('C5', [
            ['label'=>'Menimbulkan Whitecast dan Comedogenic',                'skor'=>1],
            ['label'=>'Menimbulkan Comedogenic',               'skor'=>2],
            ['label'=>'Menimbulkan Whitecast',         'skor'=>3],
            ['label'=>'Tidak Menimbulkan Whitecast dan Comedogenic', 'skor'=>4],
        ], true);

       
        $add('C6', [
            ['label'=>'Spray', 'skor'=>1],
            ['label'=>'Lotion',   'skor'=>2],
            ['label'=>'Gel',   'skor'=>3],
            ['label'=>'Krim', 'skor'=>4],
        ], true);
        $add('C7', [
            ['label'=>'15-25 ml',  'skor'=>1, 'min_val'=>15,  'max_val'=>25],
            ['label'=>'26-40 ml', 'skor'=>2, 'min_val'=>26, 'max_val'=>40],
            ['label'=>'41-55 ml', 'skor'=>3, 'min_val'=>41, 'max_val'=>55],
            ['label'=>'>55 ml',  'skor'=>4, 'min_val'=>56, 'max_val'=>100],
        ], true);
    }
}
