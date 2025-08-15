<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index untuk tabel alternatifs
        Schema::table('alternatifs', function (Blueprint $table) {
            if (!Schema::hasIndex('alternatifs', 'alternatifs_jenis_kulit_index')) {
                $table->index('jenis_kulit');
            }
            if (!Schema::hasIndex('alternatifs', 'alternatifs_harga_index')) {
                $table->index('harga');
            }
            if (!Schema::hasIndex('alternatifs', 'alternatifs_spf_index')) {
                $table->index('spf');
            }
            if (!Schema::hasIndex('alternatifs', 'alternatifs_kode_produk_index')) {
                $table->index('kode_produk');
            }
        });

        // Index untuk tabel penilaians
        Schema::table('penilaians', function (Blueprint $table) {
            if (!Schema::hasIndex('penilaians', 'penilaians_alternatif_kriteria_index')) {
                $table->index(['alternatif_id', 'kriteria_id']);
            }
        });

        // Index untuk tabel nilai_akhirs
        Schema::table('nilai_akhirs', function (Blueprint $table) {
            if (!Schema::hasIndex('nilai_akhirs', 'nilai_akhirs_total_index')) {
                $table->index('total');
            }
            if (!Schema::hasIndex('nilai_akhirs', 'nilai_akhirs_peringkat_index')) {
                $table->index('peringkat');
            }
        });

        // Index untuk tabel permintaans
        Schema::table('permintaans', function (Blueprint $table) {
            if (!Schema::hasIndex('permintaans', 'permintaans_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('permintaans', 'permintaans_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            $table->dropIndex(['jenis_kulit']);
            $table->dropIndex(['harga']);
            $table->dropIndex(['spf']);
            $table->dropIndex(['kode_produk']);
        });

        Schema::table('penilaians', function (Blueprint $table) {
            $table->dropIndex(['alternatif_id', 'kriteria_id']);
        });

        Schema::table('nilai_akhirs', function (Blueprint $table) {
            $table->dropIndex(['total']);
            $table->dropIndex(['peringkat']);
        });

        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
    }
};