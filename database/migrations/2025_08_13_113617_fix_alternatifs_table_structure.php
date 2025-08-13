<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel alternatifs menggunakan struktur yang benar
        if (Schema::hasTable('alternatifs')) {
            Schema::table('alternatifs', function (Blueprint $table) {
                // Jika ada kolom nis, hapus dan ganti dengan kode_produk
                if (Schema::hasColumn('alternatifs', 'nis') && !Schema::hasColumn('alternatifs', 'kode_produk')) {
                    $table->renameColumn('nis', 'kode_produk');
                }
                
                // Jika ada kolom nama_siswa, hapus dan ganti dengan nama_produk
                if (Schema::hasColumn('alternatifs', 'nama_siswa') && !Schema::hasColumn('alternatifs', 'nama_produk')) {
                    $table->renameColumn('nama_siswa', 'nama_produk');
                }
                
                // Jika ada kolom jk, hapus dan ganti dengan jenis_kulit
                if (Schema::hasColumn('alternatifs', 'jk') && !Schema::hasColumn('alternatifs', 'jenis_kulit')) {
                    $table->renameColumn('jk', 'jenis_kulit');
                }
                
                // Jika ada kolom kelas, hapus karena tidak diperlukan untuk produk
                if (Schema::hasColumn('alternatifs', 'kelas')) {
                    $table->dropColumn('kelas');
                }
            });
        }
    }

    public function down(): void
    {
        // Reverse the changes if needed
        if (Schema::hasTable('alternatifs')) {
            Schema::table('alternatifs', function (Blueprint $table) {
                if (Schema::hasColumn('alternatifs', 'kode_produk') && !Schema::hasColumn('alternatifs', 'nis')) {
                    $table->renameColumn('kode_produk', 'nis');
                }
                
                if (Schema::hasColumn('alternatifs', 'nama_produk') && !Schema::hasColumn('alternatifs', 'nama_siswa')) {
                    $table->renameColumn('nama_produk', 'nama_siswa');
                }
                
                if (Schema::hasColumn('alternatifs', 'jenis_kulit') && !Schema::hasColumn('alternatifs', 'jk')) {
                    $table->renameColumn('jenis_kulit', 'jk');
                }
            });
        }
    }
};