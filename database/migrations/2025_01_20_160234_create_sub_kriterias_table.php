<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel sub kriteria
     */
    public function up(): void
    {
        if (!Schema::hasTable('sub_kriterias')) {
            Schema::create('sub_kriterias', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kriteria_id')
                    ->constrained('kriterias')
                    ->cascadeOnDelete();
                $table->string('label', 100)->nullable(); // Label sub kriteria
                $table->unsignedTinyInteger('skor')->nullable(); // Skor 1-4
                $table->integer('min_val')->nullable(); // Nilai minimum range
                $table->integer('max_val')->nullable(); // Nilai maksimum range
                $table->timestamps();
                
                // Index untuk optimasi query
                $table->index(['kriteria_id', 'skor']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kriterias');
    }
};
