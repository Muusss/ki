<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel nilai akhir dan peringkat
     */
    public function up(): void
    {
        if (!Schema::hasTable('nilai_akhirs')) {
            Schema::create('nilai_akhirs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alternatif_id')
                    ->constrained('alternatifs')
                    ->cascadeOnDelete();
                $table->decimal('total', 10, 6)->default(0)->index(); // Total nilai
                $table->unsignedInteger('peringkat')->nullable()->index(); // Ranking
                $table->unsignedBigInteger('periode_id')->nullable()->index(); // Optional periode
                $table->timestamps();
                
                // Unique constraint untuk satu alternatif per periode
                $table->unique(['alternatif_id', 'periode_id']);
                
                // Index untuk sorting
                $table->index(['periode_id', 'peringkat']);
                $table->index(['periode_id', 'total']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akhirs');
    }
};