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
        'spf',
        'status',
        'admin_notes',
        'gambar', 
    ];

    protected $casts = [
        'komposisi' => 'array', // Jika ingin simpan sebagai JSON
    ];

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get status color for badge
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'orange',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }
}