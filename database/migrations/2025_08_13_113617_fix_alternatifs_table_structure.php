<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('alternatifs')) {
            Schema::create('alternatifs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_produk', 30)->unique();
                $table->string('nama_produk', 100);
                $table->enum('jenis_kulit', ['normal', 'berminyak', 'kering', 'kombinasi']);
                $table->integer('harga')->nullable();
                $table->integer('spf')->nullable();
                $table->string('gambar')->nullable();
                $table->timestamps();
                
                $table->index('jenis_kulit');
                $table->index('harga');
                $table->index('spf');
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