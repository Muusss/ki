<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Alternatif extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'nama_produk', 
        'jenis_kulit',
        'harga',
        'spf',
        'gambar'
    ];

    protected $appends = ['has_gambar', 'gambar_url', 'harga_format', 'spf_label'];

    /**
     * Accessor untuk cek apakah produk memiliki gambar
     */
    public function getHasGambarAttribute()
    {
        return !empty($this->gambar) && File::exists(public_path('img/produk/' . $this->gambar));
    }

    /**
     * Accessor untuk mendapatkan URL gambar lengkap
     */
    public function getGambarUrlAttribute()
    {
        if ($this->has_gambar) {
            return asset('img/produk/' . $this->gambar);
        }
        return null;
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormatAttribute()
    {
        if (!is_null($this->harga)) {
            return 'Rp ' . number_format($this->harga, 0, ',', '.');
        }
        return null;
    }

    /**
     * Accessor untuk SPF label
     */
    public function getSpfLabelAttribute()
    {
        if (!is_null($this->spf) && $this->spf !== '') {
            if ($this->spf >= 60) {
                return '50+';
            }
            return $this->spf;
        }
        return null;
    }

    /**
     * Relasi dengan tabel penilaian
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    /**
     * Relasi dengan tabel nilai_akhir
     */
    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhir::class);
    }
}