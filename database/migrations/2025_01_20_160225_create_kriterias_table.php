<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations untuk tabel kriteria SPK
     */
    public function up(): void
    {
        if (!Schema::hasTable('kriterias')) {
            Schema::create('kriterias', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 10)->unique(); // Kode kriteria (C1, C2, dst)
                $table->string('kriteria', 100); // Nama kriteria
                $table->enum('atribut', ['benefit', 'cost'])->index(); // Jenis atribut
                $table->unsignedTinyInteger('urutan_prioritas'); // Prioritas untuk ROC
                $table->decimal('bobot_roc', 8, 6)->nullable(); // Bobot hasil perhitungan ROC
                $table->timestamps();
                
                // Index untuk optimasi query
                $table->index('urutan_prioritas');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
