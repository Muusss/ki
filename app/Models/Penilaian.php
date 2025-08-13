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

    // Update method tanpa parameter User
    public static function normalisasiSMART(?int $periodeId = null): void
    {
        $kriterias = Kriteria::all();
        foreach ($kriterias as $kr) {
            $q = static::query()
                ->where('kriteria_id', $kr->id)
                ->periode($periodeId);

            $min = (clone $q)->min('nilai_asli');
            $max = (clone $q)->max('nilai_asli');

            $rows = $q->get(['id','nilai_asli']);
            if ($rows->isEmpty()) continue;

            foreach ($rows as $row) {
                if ($max == $min) {
                    $u = 1.0;
                } else {
                    $u = ($kr->atribut === 'cost')
                        ? ($max - $row->nilai_asli) / ($max - $min)
                        : ($row->nilai_asli - $min) / ($max - $min);
                }
                static::where('id', $row->id)->update(['nilai_normal' => round($u, 6)]);
            }
        }
    }
}