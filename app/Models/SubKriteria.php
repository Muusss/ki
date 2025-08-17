<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriterias';

    protected $fillable = [
        'kriteria_id',
        'label',
        'skor',
        'min_val',
        'max_val'
    ];

    protected $casts = [
        'skor' => 'integer',
        'min_val' => 'integer',
        'max_val' => 'integer'
    ];

    /**
     * Relasi dengan Kriteria
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    /**
     * Scope untuk filter by kode kriteria
     */
    public function scopeByKodeKriteria($query, $kode)
    {
        return $query->whereHas('kriteria', function($q) use ($kode) {
            $q->where('kode', $kode);
        });
    }

    /**
     * Validasi skor harus 1-4
     */
    public function setSkorAttribute($value)
    {
        $this->attributes['skor'] = max(1, min(4, (int)$value));
    }
}