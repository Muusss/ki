<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel alternatif/menu
     */
    public function up(): void
    {
        if (!Schema::hasTable('alternatifs')) {
            Schema::create('alternatifs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_menu', 30)->unique(); // Kode unik menu
                $table->string('nama_menu', 100)->index(); // Nama menu
                $table->enum('jenis_menu', [
                    'makanan', 
                    'cemilan', 
                    'coffee', 
                    'milkshake', 
                    'mojito', 
                    'yakult', 
                    'tea'
                ])->index(); // Jenis menu
                $table->enum('harga', [
                    '<=20000', 
                    '>20000-<=25000', 
                    '>25000-<=30000', 
                    '>30000'
                ])->index(); // Kategori harga
                $table->string('gambar')->nullable(); // Path gambar menu
                $table->timestamps();

                // Index tambahan
                $table->index(['jenis_menu', 'harga']);
            });
        } else {
            Schema::table('alternatifs', function (Blueprint $table) {
                // Hapus kolom lama jika ada
                if (Schema::hasColumn('alternatifs', 'kode_produk')) {
                    $table->dropColumn(['kode_produk', 'nama_produk', 'jenis_kulit', 'harga', 'spf', 'gambar']);
                }
                // Tambahkan kolom baru
                if (!Schema::hasColumn('alternatifs', 'kode_menu')) {
                    $table->string('kode_menu', 30)->unique();
                }
                if (!Schema::hasColumn('alternatifs', 'nama_menu')) {
                    $table->string('nama_menu', 100)->index();
                }
                if (!Schema::hasColumn('alternatifs', 'jenis_menu')) {
                    $table->enum('jenis_menu', [
                        'makanan', 'cemilan', 'coffee', 
                        'milkshake', 'mojito', 'yakult', 'tea'
                    ])->index();
                }
                if (!Schema::hasColumn('alternatifs', 'harga')) {
                    $table->enum('harga', [
                        '<=20000', '>20000-<=25000', 
                        '>25000-<=30000', '>30000'
                    ])->index();
                }
                if (!Schema::hasColumn('alternatifs', 'gambar')) {
                    $table->string('gambar')->nullable();
                }
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
