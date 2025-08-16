<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel normalisasi bobot kriteria
     */
    public function up(): void
    {
        if (!Schema::hasTable('normalisasi_bobots')) {
            Schema::create('normalisasi_bobots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kriteria_id')
                    ->constrained('kriterias')
                    ->cascadeOnDelete();
                $table->decimal('normalisasi', 15, 8); // Nilai normalisasi bobot
                $table->unsignedBigInteger('periode_id')->nullable()->index(); // Optional periode
                $table->timestamps();
                
                // Unique constraint
                $table->unique(['kriteria_id', 'periode_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normalisasi_bobots');
    }
};