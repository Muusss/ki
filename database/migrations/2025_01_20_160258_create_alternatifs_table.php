<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alternatifs', function (Blueprint $t) {
            $t->id();
            $t->string('kode_produk',30)->unique();
            $t->string('nama_produk',100);
            $t->enum('jenis_kulit',['normal','berminyak','kering','kombinasi']);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatifs');
    }
};
