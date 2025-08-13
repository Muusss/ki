<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Penilaian extends Model
{
    protected $table = 'penilaians';

    protected $fillable = [
        'alternatif_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai_asli',
        'nilai_normal',
    ];

    protected $casts = [
        'nilai_asli' => 'float',
        'nilai_normal' => 'float',
    ];

    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function subKriteria(): BelongsTo
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }

    public function scopePeriode(Builder $q, ?int $periodeId): Builder
    {
        if ($periodeId !== null && Schema::hasColumn($this->getTable(), 'periode_id')) {
            $q->where('periode_id', $periodeId);
        }
        return $q;
    }

    public function scopeForKriteria(Builder $q, int $kriteriaId): Builder
    {
        return $q->where('kriteria_id', $kriteriaId);
    }

    public function setNilaiAsliAttribute($value): void
    {
        $v = (int) $value;
        $this->attributes['nilai_asli'] = max(1, min(4, $v));
    }

    /**
     * Normalisasi SMART dengan pembedaan benefit dan cost
     * 
     * @param int|null $periodeId
     * @return void
     */
    public static function normalisasiSMART(?int $periodeId = null): void
    {
        $kriterias = Kriteria::all();
        
        foreach ($kriterias as $kriteria) {
            // Ambil semua penilaian untuk kriteria ini
            $query = static::query()
                ->where('kriteria_id', $kriteria->id)
                ->periode($periodeId);

            $min = (clone $query)->min('nilai_asli');
            $max = (clone $query)->max('nilai_asli');

            $rows = $query->get(['id', 'nilai_asli']);
            
            if ($rows->isEmpty()) continue;

            foreach ($rows as $row) {
                $nilaiUtility = 0;
                
                if ($max == $min) {
                    // Jika semua nilai sama, utility = 1
                    $nilaiUtility = 1.0;
                } else {
                    // Hitung utility berdasarkan jenis kriteria
                    if ($kriteria->atribut === 'benefit') {
                        // Untuk kriteria benefit: semakin tinggi semakin baik
                        // Formula: (Xi - Xmin) / (Xmax - Xmin)
                        $nilaiUtility = ($row->nilai_asli - $min) / ($max - $min);
                    } else {
                        // Untuk kriteria cost: semakin rendah semakin baik
                        // Formula: (Xmax - Xi) / (Xmax - Xmin)
                        $nilaiUtility = ($max - $row->nilai_asli) / ($max - $min);
                    }
                }

                // Update nilai normal dengan hasil perhitungan utility
                static::where('id', $row->id)->update([
                    'nilai_normal' => round($nilaiUtility, 6)
                ]);
            }
        }
    }

    /**
     * Hitung utility untuk satu nilai spesifik
     * 
     * @param float $nilai
     * @param float $min
     * @param float $max
     * @param string $atribut ('benefit' atau 'cost')
     * @return float
     */
    public static function hitungUtility(float $nilai, float $min, float $max, string $atribut): float
    {
        if ($max == $min) {
            return 1.0;
        }

        if ($atribut === 'benefit') {
            // Benefit: nilai tinggi = utility tinggi
            return ($nilai - $min) / ($max - $min);
        } else {
            // Cost: nilai rendah = utility tinggi
            return ($max - $nilai) / ($max - $min);
        }
    }

    /**
     * Get informasi normalisasi untuk debugging
     * 
     * @return array
     */
    public static function getInfoNormalisasi(): array
    {
        $kriterias = Kriteria::all();
        $info = [];

        foreach ($kriterias as $kriteria) {
            $penilaians = static::where('kriteria_id', $kriteria->id)->get();
            
            if ($penilaians->isEmpty()) continue;

            $min = $penilaians->min('nilai_asli');
            $max = $penilaians->max('nilai_asli');

            $info[] = [
                'kriteria' => $kriteria->kode . ' - ' . $kriteria->kriteria,
                'atribut' => $kriteria->atribut,
                'min' => $min,
                'max' => $max,
                'range' => $max - $min,
                'formula' => $kriteria->atribut === 'benefit' 
                    ? '(Xi - ' . $min . ') / (' . $max . ' - ' . $min . ')'
                    : '(' . $max . ' - Xi) / (' . $max . ' - ' . $min . ')'
            ];
        }

        return $info;
    }
}