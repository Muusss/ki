<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel alternatifs menggunakan struktur yang benar
        if (!Schema::hasTable('alternatifs')) {
            Schema::create('alternatifs', function (Blueprint $table) {
                $table->id();
                $table->string('kode_produk', 30)->unique();
                $table->string('nama_produk', 100);
                $table->enum('jenis_kulit', ['normal', 'berminyak', 'kering', 'kombinasi', 'sensitif']);
                $table->timestamps();
            });
        } else {
            // Ubah struktur jika sudah ada tapi salah
            Schema::table('alternatifs', function (Blueprint $table) {
                // Cek dan perbaiki kolom yang salah
                if (Schema::hasColumn('alternatifs', 'nis') && !Schema::hasColumn('alternatifs', 'kode_produk')) {
                    $table->renameColumn('nis', 'kode_produk');
                }
                
                if (Schema::hasColumn('alternatifs', 'nama_siswa') && !Schema::hasColumn('alternatifs', 'nama_produk')) {
                    $table->renameColumn('nama_siswa', 'nama_produk');
                }
                
                if (Schema::hasColumn('alternatifs', 'jk') && !Schema::hasColumn('alternatifs', 'jenis_kulit')) {
                    $table->renameColumn('jk', 'jenis_kulit');
                }
                
                // Hapus kolom yang tidak diperlukan
                if (Schema::hasColumn('alternatifs', 'kelas')) {
                    $table->dropColumn('kelas');
                }
            });
        }

        // Pastikan tabel nilai_akhirs ada
        if (!Schema::hasTable('nilai_akhirs')) {
            Schema::create('nilai_akhirs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alternatif_id')->constrained('alternatifs')->cascadeOnDelete();
                $table->decimal('total', 10, 6)->default(0);
                $table->unsignedInteger('peringkat')->nullable();
                $table->unsignedBigInteger('periode_id')->nullable()->index();
                $table->timestamps();
                
                $table->unique(['alternatif_id', 'periode_id']);
            });
        }

        // Insert some sample data jika tabel kosong
        if (DB::table('alternatifs')->count() == 0) {
            DB::table('alternatifs')->insert([
                [
                    'kode_produk' => 'PRD001',
                    'nama_produk' => 'OMG Sunscreen Daily Protection',
                    'jenis_kulit' => 'normal',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'kode_produk' => 'PRD002',
                    'nama_produk' => 'Wardah UV Shield Essential',
                    'jenis_kulit' => 'berminyak',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'kode_produk' => 'PRD003',
                    'nama_produk' => 'Azarine Hydrasoothe Sunscreen',
                    'jenis_kulit' => 'kering',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'kode_produk' => 'PRD004',
                    'nama_produk' => 'Emina Sun Protection',
                    'jenis_kulit' => 'kombinasi',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'kode_produk' => 'PRD005',
                    'nama_produk' => 'COSRX Aloe Soothing Sun Cream',
                    'jenis_kulit' => 'normal',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]);
        }
    }

    public function down(): void
    {
        // Rollback if needed
    }
};