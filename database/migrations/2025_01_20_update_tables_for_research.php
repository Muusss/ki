// database/migrations/2025_01_20_update_tables_for_research.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Update tabel kriterias dengan urutan prioritas
        Schema::table('kriterias', function (Blueprint $table) {
            if (!Schema::hasColumn('kriterias', 'urutan_prioritas')) {
                $table->unsignedTinyInteger('urutan_prioritas')->after('atribut');
            }
            if (!Schema::hasColumn('kriterias', 'bobot_roc')) {
                $table->decimal('bobot_roc', 8, 6)->nullable()->after('urutan_prioritas');
            }
        });
    }

    public function down(): void
    {
        //
    }
};