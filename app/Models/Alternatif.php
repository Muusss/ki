<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Alternatif extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'alternatifs';

    /**
     * Field yang bisa diisi mass assignment
     */
    protected $fillable = [
        'kode_menu',
        'nama_menu', 
        'jenis_menu',
        'harga',
        'gambar'
    ];

    /**
     * Accessor tambahan yang akan selalu ada di response
     */
    protected $appends = ['has_gambar', 'gambar_url', 'harga_label', 'jenis_menu_label'];

    /**
     * Konstanta untuk jenis menu
     */
    const JENIS_MENU = [
        'makanan' => 'Makanan',
        'cemilan' => 'Cemilan', 
        'coffee' => 'Coffee',
        'milkshake' => 'Milkshake',
        'mojito' => 'Mojito',
        'yakult' => 'Yakult',
        'tea' => 'Tea'
    ];

    /**
     * Konstanta untuk kategori harga
     */
    const KATEGORI_HARGA = [
        '<=20000' => 'Rp 20.000 ke bawah',
        '>20000-<=25000' => 'Rp 20.001 - Rp 25.000',
        '>25000-<=30000' => 'Rp 25.001 - Rp 30.000',
        '>30000' => 'Di atas Rp 30.000'
    ];

    /**
     * Get range harga numerik untuk filtering
     */
    const HARGA_RANGE = [
        '<=20000' => ['min' => 0, 'max' => 20000],
        '>20000-<=25000' => ['min' => 20001, 'max' => 25000],
        '>25000-<=30000' => ['min' => 25001, 'max' => 30000],
        '>30000' => ['min' => 30001, 'max' => 999999]
    ];

    /**
     * Accessor untuk cek apakah menu memiliki gambar
     */
    public function getHasGambarAttribute()
    {
        return !empty($this->gambar) && File::exists(public_path('img/menu/' . $this->gambar));
    }

    /**
     * Accessor untuk mendapatkan URL gambar lengkap
     */
    public function getGambarUrlAttribute()
    {
        if ($this->has_gambar) {
            return asset('img/menu/' . $this->gambar);
        }
        // Return default image based on jenis_menu
        return $this->getDefaultImage();
    }

    /**
     * Get default image berdasarkan jenis menu
     */
    private function getDefaultImage()
    {
        $defaults = [
            'makanan' => 'img/menu/default-makanan.jpg',
            'cemilan' => 'img/menu/default-cemilan.jpg',
            'coffee' => 'img/menu/default-coffee.jpg',
            'milkshake' => 'img/menu/default-milkshake.jpg',
            'mojito' => 'img/menu/default-mojito.jpg',
            'yakult' => 'img/menu/default-yakult.jpg',
            'tea' => 'img/menu/default-tea.jpg'
        ];

        $jenis = $this->jenis_menu ?? 'makanan';
        return asset($defaults[$jenis] ?? $defaults['makanan']);
    }

    /**
     * Accessor untuk label harga yang lebih readable
     */
    public function getHargaLabelAttribute()
    {
        return self::KATEGORI_HARGA[$this->harga] ?? $this->harga;
    }

    /**
     * Accessor untuk label jenis menu
     */
    public function getJenisMenuLabelAttribute()
    {
        return self::JENIS_MENU[$this->jenis_menu] ?? $this->jenis_menu;
    }

    /**
     * Get harga minimum dari kategori
     */
    public function getHargaMinAttribute()
    {
        return self::HARGA_RANGE[$this->harga]['min'] ?? 0;
    }

    /**
     * Get harga maksimum dari kategori
     */
    public function getHargaMaxAttribute()
    {
        return self::HARGA_RANGE[$this->harga]['max'] ?? 999999;
    }

    /**
     * Scope untuk filter berdasarkan jenis menu
     */
    public function scopeJenisMenu($query, $jenis)
    {
        if ($jenis && $jenis !== 'all') {
            return $query->where('jenis_menu', $jenis);
        }
        return $query;
    }

    /**
     * Scope untuk filter berdasarkan kategori harga
     */
    public function scopeKategoriHarga($query, $kategori)
    {
        if ($kategori && $kategori !== 'all') {
            return $query->where('harga', $kategori);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian nama menu
     */
    public function scopeSearch($query, $keyword)
    {
        if ($keyword) {
            return $query->where('nama_menu', 'LIKE', "%{$keyword}%")
                        ->orWhere('kode_menu', 'LIKE', "%{$keyword}%");
        }
        return $query;
    }

    /**
     * Relasi dengan tabel penilaian (tetap untuk SPK)
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }

    /**
     * Relasi dengan tabel nilai_akhir (tetap untuk SPK)
     */
    public function nilaiAkhir()
    {
        return $this->hasOne(NilaiAkhir::class);
    }

    /**
     * Generate kode menu otomatis
     */
    public static function generateKodeMenu($jenisMenu = 'makanan')
    {
        // Prefix berdasarkan jenis menu
        $prefixes = [
            'makanan' => 'MKN',
            'cemilan' => 'CML',
            'coffee' => 'COF',
            'milkshake' => 'MLK',
            'mojito' => 'MJT',
            'yakult' => 'YKT',
            'tea' => 'TEA'
        ];

        $prefix = $prefixes[$jenisMenu] ?? 'MNU';
        
        // Get last kode with same prefix
        $lastKode = self::where('kode_menu', 'LIKE', $prefix . '%')
                       ->orderBy('kode_menu', 'desc')
                       ->first();

        if ($lastKode) {
            // Extract number and increment
            $number = intval(substr($lastKode->kode_menu, strlen($prefix)));
            $newNumber = $number + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method untuk auto generate kode
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_menu)) {
                $model->kode_menu = self::generateKodeMenu($model->jenis_menu);
            }
        });
    }
}