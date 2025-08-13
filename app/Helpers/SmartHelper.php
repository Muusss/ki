<?php

namespace App\Helpers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\NilaiAkhir;

class SmartHelper
{
    /**
     * Normalisasi nilai utilitas SMART dengan pembedaan benefit vs cost
     */
    public static function hitungNilaiUtilitas($nilai, $min, $max, $atribut = 'benefit')
    {
        if ($max == $min) return 1;
        
        if ($atribut == 'benefit') {
            // Benefit: semakin tinggi semakin baik
            return ($nilai - $min) / ($max - $min);
        } else {
            // Cost: semakin rendah semakin baik  
            return ($max - $nilai) / ($max - $min);
        }
    }

    /**
     * Hitung nilai akhir dengan ROC + SMART
     */
    public static function hitungNilaiAkhir($alternatifId, $periodeId = null)
    {
        $kriterias = Kriteria::all();
        $totalNilai = 0;

        foreach ($kriterias as $kriteria) {
            $penilaian = Penilaian::where('alternatif_id', $alternatifId)
                                  ->where('kriteria_id', $kriteria->id)
                                  ->first();
            
            if ($penilaian && $penilaian->nilai_normal) {
                // Nilai utilitas x Bobot ROC
                $nilaiKriteria = $penilaian->nilai_normal * $kriteria->bobot_roc;
                $totalNilai += $nilaiKriteria;
            }
        }

        return $totalNilai;
    }

    /**
     * Generate ranking produk
     */
    public static function generateRanking($kelasFilter = null)
    {
        $query = NilaiAkhir::with('alternatif');
        
        if ($kelasFilter) {
            $query->whereHas('alternatif', function($q) use ($kelasFilter) {
                $q->where('jenis_kulit', $kelasFilter);
            });
        }

        $results = $query->orderByDesc('total')->get();
        
        $rank = 1;
        foreach ($results as $result) {
            $result->peringkat = $rank++;
            $result->save();
        }

        return $results;
    }

    /**
     * Contoh perhitungan untuk masing-masing kriteria
     */
    public static function getContohPerhitungan()
    {
        $examples = [];
        $kriterias = Kriteria::with(['penilaians' => function($query) {
            $query->take(3); // Ambil 3 contoh
        }])->get();

        foreach ($kriterias as $kriteria) {
            if ($kriteria->penilaians->count() > 0) {
                $min = $kriteria->penilaians->min('nilai_asli');
                $max = $kriteria->penilaians->max('nilai_asli');
                
                $contohData = [];
                foreach ($kriteria->penilaians as $penilaian) {
                    $utility = self::hitungNilaiUtilitas(
                        $penilaian->nilai_asli, 
                        $min, 
                        $max, 
                        $kriteria->atribut
                    );
                    
                    $contohData[] = [
                        'nilai_asli' => $penilaian->nilai_asli,
                        'utility' => round($utility, 4),
                        'formula' => $kriteria->atribut === 'benefit' 
                            ? "({$penilaian->nilai_asli} - {$min}) / ({$max} - {$min}) = " . round($utility, 4)
                            : "({$max} - {$penilaian->nilai_asli}) / ({$max} - {$min}) = " . round($utility, 4)
                    ];
                }

                $examples[] = [
                    'kriteria' => $kriteria->kode . ' - ' . $kriteria->kriteria,
                    'atribut' => $kriteria->atribut,
                    'min' => $min,
                    'max' => $max,
                    'contoh' => $contohData,
                    'penjelasan' => $kriteria->atribut === 'benefit' 
                        ? 'Nilai tinggi = utility tinggi (lebih baik)'
                        : 'Nilai rendah = utility tinggi (lebih baik/murah)'
                ];
            }
        }

        return $examples;
    }

    /**
     * Validasi konsistensi atribut kriteria
     */
    public static function validateKriteriaAttributes()
    {
        $kriterias = Kriteria::all();
        $validations = [];

        foreach ($kriterias as $kriteria) {
            $validation = [
                'kriteria' => $kriteria->kode . ' - ' . $kriteria->kriteria,
                'atribut' => $kriteria->atribut,
                'is_valid' => true,
                'message' => 'OK'
            ];

            // Contoh validasi logika bisnis untuk sunscreen
            switch (strtolower($kriteria->kriteria)) {
                case (strpos(strtolower($kriteria->kriteria), 'harga') !== false):
                    if ($kriteria->atribut !== 'cost') {
                        $validation['is_valid'] = false;
                        $validation['message'] = 'Harga seharusnya cost (semakin murah semakin baik)';
                    }
                    break;
                    
                case (strpos(strtolower($kriteria->kriteria), 'spf') !== false):
                    if ($kriteria->atribut !== 'benefit') {
                        $validation['is_valid'] = false;
                        $validation['message'] = 'SPF seharusnya benefit (semakin tinggi semakin baik)';
                    }
                    break;

                case (strpos(strtolower($kriteria->kriteria), 'efek samping') !== false):
                    if ($kriteria->atribut !== 'cost') {
                        $validation['is_valid'] = false;
                        $validation['message'] = 'Efek samping seharusnya cost (semakin sedikit semakin baik)';
                    }
                    break;
            }

            $validations[] = $validation;
        }

        return $validations;
    }
}