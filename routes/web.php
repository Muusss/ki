<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SMARTController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\PermintaanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Semua route butuh login saja (tanpa admin)
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-akhir', [DashboardController::class, 'hasilAkhir'])->name('hasil-akhir');

    // PDF
    Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');

    // Kriteria
    Route::prefix('kriteria')->group(function () {
        Route::get('/', [KriteriaController::class, 'index'])->name('kriteria');
        Route::post('/store', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::get('/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
        Route::post('/update', [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::post('/delete', [KriteriaController::class, 'delete'])->name('kriteria.delete');
    });

    // Sub-Kriteria
    Route::prefix('sub-kriteria')->group(function () {
        Route::get('/', [SubKriteriaController::class, 'index'])->name('subkriteria');
        Route::post('/store', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
        Route::get('/edit', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
        Route::post('/update', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
        Route::post('/delete', [SubKriteriaController::class, 'delete'])->name('subkriteria.delete');
    });

    // Alternatif (Produk)
    Route::prefix('alternatif')->group(function () {
        Route::get('/', [AlternatifController::class, 'index'])->name('alternatif');
        Route::post('/simpan', [AlternatifController::class, 'store'])->name('alternatif.store');
        Route::get('/ubah', [AlternatifController::class, 'edit'])->name('alternatif.edit');
        Route::post('/ubah', [AlternatifController::class, 'update'])->name('alternatif.update');
        Route::post('/hapus', [AlternatifController::class, 'delete'])->name('alternatif.delete');
    });

    // Penilaian
    Route::prefix('penilaian')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian');
        Route::get('/{id}/ubah', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::post('/{id}/ubah', [PenilaianController::class, 'update'])->name('penilaian.update');
    });

    // Permintaan
    Route::prefix('permintaan')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])->name('permintaan.index');
        Route::post('/store', [PermintaanController::class, 'store'])->name('permintaan.store');
        Route::put('/{id}', [PermintaanController::class, 'update'])->name('permintaan.update');
        Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('permintaan.destroy');
    });

    // SMART
    Route::prefix('smart')->group(function () {
        Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
        Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');
    });

    Route::get('/spk/proses', [KriteriaController::class, 'proses'])->name('spk.proses');
});

require __DIR__.'/auth.php';
