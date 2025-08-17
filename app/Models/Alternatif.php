<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';
    
    protected $fillable = [
        'kode_menu',
        'nama_menu', 
        'jenis_menu',
        'harga',
        'gambar'
    ];

    // Konstanta untuk jenis menu
    const JENIS_MENU = [
        'makanan' => 'Makanan',
        'cemilan' => 'Cemilan', 
        'coffee' => 'Coffee',
        'milkshake' => 'Milkshake',
        'mojito' => 'Mojito',
        'yakult' => 'Yakult',
        'tea' => 'Tea'
    ];

    // Konstanta untuk kategori harga
    const KATEGORI_HARGA = [
        '<=20000' => '≤ Rp 20.000',
        '>20000-<=25000' => 'Rp 20.001 - 25.000',
        '>25000-<=30000' => 'Rp 25.001 - 30.000',
        '>30000' => '> Rp 30.000'
    ];

    // Relationships
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function nilaiUtility()
    {
        return $this->hasMany(NilaiUtility::class, 'alternatif_id');
    }

    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, 'alternatif_id');
    }

    // Accessors
    public function getJenisMenuLabelAttribute()
    {
        return self::JENIS_MENU[$this->jenis_menu] ?? ucfirst($this->jenis_menu ?? '-');
    }

    public function getHargaLabelAttribute()
    {
        return self::KATEGORI_HARGA[$this->harga] ?? $this->harga ?? '-';
    }

    public function getHasGambarAttribute()
    {
        return !empty($this->gambar) && file_exists(public_path('img/menu/' . $this->gambar));
    }

    public function getGambarUrlAttribute()
    {
        if ($this->has_gambar) {
            return asset('img/menu/' . $this->gambar);
        }
        return null;
    }

    // Scopes - PENTING: Tambahkan scope yang hilang
    public function scopeJenis($query, $jenis)
    {
        if ($jenis && $jenis !== 'all') {
            return $query->where('jenis_menu', $jenis);
        }
        return $query;
    }

    public function scopeHarga($query, $harga)
    {
        if ($harga && $harga !== 'all') {
            return $query->where('harga', $harga);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('nama_menu', 'like', '%' . $search . '%')
                  ->orWhere('kode_menu', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
}