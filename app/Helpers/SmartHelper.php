<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
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
        // Load semua data sekaligus
        $kriterias = Kriteria::all();
        $penilaians = Penilaian::where('alternatif_id', $alternatifId)
                            ->whereIn('kriteria_id', $kriterias->pluck('id'))
                            ->get()
                            ->keyBy('kriteria_id');
        
        $totalNilai = 0;
        
        foreach ($kriterias as $kriteria) {
            $penilaian = $penilaians->get($kriteria->id);
            
            if ($penilaian && $penilaian->nilai_normal) {
                $nilaiKriteria = $penilaian->nilai_normal * $kriteria->bobot_roc;
                $totalNilai += $nilaiKriteria;
            }
        }
        
        return $totalNilai;
    }

    /**
     * Generate ranking menu
     */
    public static function generateRanking($jenisMenuFilter = null)
    {
        $query = NilaiAkhir::with('alternatif');
        
        if ($jenisMenuFilter) {
            $query->whereHas('alternatif', function($q) use ($jenisMenuFilter) {
                $q->where('jenis_menu', $jenisMenuFilter);
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
     * Validasi konsistensi atribut kriteria untuk menu cafe
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

            // Validasi logika bisnis untuk menu cafe
            $kriteriaName = strtolower($kriteria->kriteria);
            
            // Harga seharusnya cost
            if (strpos($kriteriaName, 'harga') !== false) {
                if ($kriteria->atribut !== 'cost') {
                    $validation['is_valid'] = false;
                    $validation['message'] = 'Harga seharusnya cost (semakin murah semakin baik)';
                }
            }
            
            // Rasa, kualitas, porsi seharusnya benefit
            if (strpos($kriteriaName, 'rasa') !== false || 
                strpos($kriteriaName, 'kualitas') !== false ||
                strpos($kriteriaName, 'porsi') !== false) {
                if ($kriteria->atribut !== 'benefit') {
                    $validation['is_valid'] = false;
                    $validation['message'] = ucfirst(explode(' ', $kriteriaName)[0]) . ' seharusnya benefit (semakin tinggi semakin baik)';
                }
            }
            
            // Waktu penyajian seharusnya cost
            if (strpos($kriteriaName, 'waktu') !== false || 
                strpos($kriteriaName, 'lama') !== false) {
                if ($kriteria->atribut !== 'cost') {
                    $validation['is_valid'] = false;
                    $validation['message'] = 'Waktu penyajian seharusnya cost (semakin cepat semakin baik)';
                }
            }

            $validations[] = $validation;
        }

        return $validations;
    }
}