// database/migrations/2025_01_22_000002_create_permintaans_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permintaans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk', 100);
            $table->text('komposisi');
            $table->enum('harga', ['< 50k', '50-100k', '> 100k']); // < 50k, 50-100k, > 100k
            $table->enum('spf', ['30', '35', '40', '50+']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};