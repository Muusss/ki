<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Backup data lama
        $oldData = DB::table('permintaans')->get();
        
        Schema::table('permintaans', function (Blueprint $table) {
            // Drop kolom lama
            $table->dropColumn('harga');
        });
        
        Schema::table('permintaans', function (Blueprint $table) {
            // Buat kolom baru dengan enum yang benar (tanpa spasi)
            $table->enum('harga', ['<50k', '50-100k', '>100k'])->after('komposisi');
        });
        
        // Restore data dengan mapping yang benar
        foreach ($oldData as $row) {
            $harga = $row->harga;
            // Mapping dari format lama ke format baru
            if (strpos($harga, '< 50k') !== false || $harga === '< 50k') {
                $harga = '<50k';
            } elseif (strpos($harga, '> 100k') !== false || $harga === '> 100k') {
                $harga = '>100k';
            } else {
                $harga = '50-100k';
            }
            
            DB::table('permintaans')
                ->where('id', $row->id)
                ->update(['harga' => $harga]);
        }
    }

    public function down(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
        
        Schema::table('permintaans', function (Blueprint $table) {
            $table->enum('harga', ['< 50k', '50-100k', '> 100k'])->after('komposisi');
        });
    }
};