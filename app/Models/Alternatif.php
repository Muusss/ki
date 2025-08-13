<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';

    protected $fillable = [
        'kode_produk',
        'nama_produk', 
        'jenis_kulit'
    ];

    // Relasi tetap sama
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhir::class, 'alternatif_id');
    }
}