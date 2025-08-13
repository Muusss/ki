<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'permintaans';

    protected $fillable = [
        'nama_produk',
        'komposisi',
        'harga',
        'spf'
    ];

    protected $casts = [
        'komposisi' => 'array', // Jika ingin simpan sebagai JSON
    ];
}