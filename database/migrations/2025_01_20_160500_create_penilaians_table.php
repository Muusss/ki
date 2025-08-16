<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel penilaian alternatif
     */
    public function up(): void
    {
        if (!Schema::hasTable('penilaians')) {
            Schema::create('penilaians', function (Blueprint $table) {
                $table->id();
                
                // Foreign keys
                $table->foreignId('alternatif_id')
                    ->constrained('alternatifs')
                    ->cascadeOnDelete();
                $table->foreignId('kriteria_id')
                    ->constrained('kriterias')
                    ->cascadeOnDelete();
                $table->foreignId('sub_kriteria_id')
                    ->nullable()
                    ->constrained('sub_kriterias')
                    ->nullOnDelete();
                
                // Nilai penilaian
                $table->unsignedTinyInteger('nilai_asli')->default(1); // Nilai original (1-4)
                $table->decimal('nilai_normal', 8, 6)->nullable(); // Nilai ternormalisasi
                
                // Optional: periode penilaian
                $table->unsignedBigInteger('periode_id')->nullable()->index();
                
                $table->timestamps();
                
                // Unique constraint untuk mencegah duplikasi
                $table->unique(['alternatif_id', 'kriteria_id', 'periode_id']);
                
                // Index untuk optimasi query
                $table->index(['alternatif_id', 'kriteria_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
