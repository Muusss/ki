<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('normalisasi_bobots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
            $table->decimal('normalisasi', 15, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('normalisasi_bobots');
    }
};
