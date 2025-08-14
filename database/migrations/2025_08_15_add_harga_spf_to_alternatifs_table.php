// database/migrations/2025_01_23_add_harga_spf_to_alternatifs_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            $table->integer('harga')->nullable()->after('jenis_kulit');
            $table->integer('spf')->nullable()->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('alternatifs', function (Blueprint $table) {
            $table->dropColumn(['harga', 'spf']);
        });
    }
};