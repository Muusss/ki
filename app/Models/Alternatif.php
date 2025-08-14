<?php
// app/Models/Alternatif.php

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
        'jenis_kulit',
        'gambar'
    ];

    // Accessor untuk URL gambar yang diperbaiki
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            // Cek apakah file ada di public/img/produk
            if (file_exists(public_path('img/produk/' . $this->gambar))) {
                return asset('img/produk/' . $this->gambar);
            }
        }
        // Default image jika tidak ada gambar
        return asset('img/no-image.png');
    }

    // Accessor untuk cek apakah gambar ada
    public function getHasGambarAttribute()
    {
        return $this->gambar && file_exists(public_path('img/produk/' . $this->gambar));
    }

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