<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            // Menambahkan kolom 'status' jika belum ada
            if (!Schema::hasColumn('permintaans', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])
                      ->default('pending')
                      ->after('spf');
            }

            // Menambahkan kolom 'admin_notes' jika belum ada
            if (!Schema::hasColumn('permintaans', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }

            // Menambahkan index untuk kolom 'status' guna meningkatkan performa query
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaans', function (Blueprint $table) {
            // Menghapus kolom 'status' dan 'admin_notes'
            $table->dropColumn(['status', 'admin_notes']);
        });
    }
};
