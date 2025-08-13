<?php
// app/Models/Alternatif.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';

    protected $fillable = [
        'kode_produk',
        'nama_produk', 
        'jenis_kulit',
        'gambar' // Tambah field gambar
    ];

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar) {
            return Storage::url('produk/' . $this->gambar);
        }
        return asset('img/no-image.png'); // Default image
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