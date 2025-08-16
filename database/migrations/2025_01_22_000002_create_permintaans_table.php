// database/migrations/2025_01_22_000002_create_permintaans_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations untuk tabel permintaan produk baru
     */
    public function up(): void
    {
        if (!Schema::hasTable('permintaans')) {
            Schema::create('permintaans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_produk', 100);
                $table->text('komposisi'); // Komposisi/ingredients produk
                $table->enum('harga', ['<50k', '50-100k', '>100k'])->index(); // Range harga
                $table->enum('spf', ['30', '35', '40', '50+'])->index(); // Nilai SPF
                $table->enum('status', ['pending', 'approved', 'rejected'])
                    ->default('pending')
                    ->index(); // Status permintaan
                $table->text('admin_notes')->nullable(); // Catatan dari admin
                $table->timestamps();
                
                // Index untuk sorting dan filtering
                $table->index(['status', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};