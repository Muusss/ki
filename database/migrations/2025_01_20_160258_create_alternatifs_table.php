<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel alternatif/produk
     */
    public function up(): void
    {
        if (!Schema::hasTable('alternatifs')) {
            Schema::create('alternatifs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_produk', 30)->unique(); // Kode unik produk
                $table->string('nama_produk', 100)->index(); // Nama produk
                $table->enum('jenis_kulit', ['normal', 'berminyak', 'kering', 'kombinasi'])->index();
                $table->unsignedInteger('harga')->nullable()->index(); // Harga dalam rupiah
                $table->unsignedTinyInteger('spf')->nullable()->index(); // Nilai SPF
                $table->string('gambar')->nullable(); // Path gambar produk
                $table->timestamps();
                
                // Composite index untuk filter kombinasi
                $table->index(['jenis_kulit', 'harga']);
                $table->index(['jenis_kulit', 'spf']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternatifs');
    }
};