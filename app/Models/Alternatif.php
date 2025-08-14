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
        'jenis_kulit',
        'harga',        // Tambah
        'spf',          // Tambah
        'gambar'
    ];

    protected $casts = [
        'harga' => 'integer',
        'spf' => 'integer'
    ];

    // Format harga untuk display
    public function getHargaFormatAttribute()
    {
        if (!$this->harga) return '-';
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Label SPF
    public function getSpfLabelAttribute()
    {
        if (!$this->spf) return '-';
        return $this->spf >= 50 ? '50+' : (string)$this->spf;
    }

    // Kategori harga untuk filter
    public function getKategoriHargaAttribute()
    {
        if (!$this->harga) return null;
        
        if ($this->harga < 50000) return 'murah';
        if ($this->harga <= 100000) return 'sedang';
        return 'mahal';
    }

    // Existing methods tetap sama...
    public function getGambarUrlAttribute()
    {
        if ($this->gambar && file_exists(public_path('img/produk/' . $this->gambar))) {
            return asset('img/produk/' . $this->gambar);
        }
        return asset('img/no-image.png');
    }

    public function getHasGambarAttribute()
    {
        return $this->gambar && file_exists(public_path('img/produk/' . $this->gambar));
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhir::class, 'alternatif_id');
    }
}