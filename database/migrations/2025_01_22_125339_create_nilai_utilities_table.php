<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel nilai utility SMART
     */
    public function up(): void
    {
        if (!Schema::hasTable('nilai_utilities')) {
            Schema::create('nilai_utilities', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alternatif_id')
                    ->constrained('alternatifs')
                    ->cascadeOnDelete();
                $table->foreignId('kriteria_id')
                    ->constrained('kriterias')
                    ->cascadeOnDelete();
                $table->decimal('nilai', 15, 8); // Nilai utility
                $table->unsignedBigInteger('periode_id')->nullable()->index(); // Optional periode
                $table->timestamps();
                
                // Unique constraint
                $table->unique(['alternatif_id', 'kriteria_id', 'periode_id']);
                
                // Index untuk query optimization
                $table->index(['alternatif_id', 'kriteria_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_utilities');
    }
};
