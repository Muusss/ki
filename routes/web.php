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
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES (Tidak perlu login)
// ============================================

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public Pages
Route::prefix('public')->name('public.')->group(function () {
    // Halaman Jenis Kulit
    Route::get('/jenis-kulit', [PublicController::class, 'jenisKulit'])->name('jenis-kulit');
    
    // Halaman Permintaan
    Route::get('/permintaan', [PublicController::class, 'permintaan'])->name('permintaan');
    Route::post('/permintaan', [PublicController::class, 'storePermintaan'])->name('permintaan.store');
    
    // Halaman Hasil SPK
    Route::get('/hasil-spk', [PublicController::class, 'hasilSPK'])->name('hasil-spk');
});

// PDF dapat diakses publik (untuk hasil SPK)
Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');

// ============================================
// AUTHENTICATED ROUTES (Perlu login)
// ============================================

Route::middleware(['auth'])->group(function () {

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-akhir', [DashboardController::class, 'hasilAkhir'])->name('hasil-akhir');

    // Kriteria Routes
    Route::prefix('kriteria')->name('kriteria.')->group(function () {
        Route::get('/', [KriteriaController::class, 'index'])->name('index');
        Route::post('/store', [KriteriaController::class, 'store'])->name('store');
        Route::get('/edit', [KriteriaController::class, 'edit'])->name('edit');
        Route::post('/update', [KriteriaController::class, 'update'])->name('update');
        Route::post('/delete', [KriteriaController::class, 'delete'])->name('delete');
        Route::post('/proses', [KriteriaController::class, 'proses'])->name('proses');
    });
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');

    // Sub-Kriteria Routes
    Route::prefix('sub-kriteria')->name('subkriteria.')->group(function () {
        Route::get('/', [SubKriteriaController::class, 'index'])->name('index');
        Route::post('/store', [SubKriteriaController::class, 'store'])->name('store');
        Route::get('/edit', [SubKriteriaController::class, 'edit'])->name('edit');
        Route::post('/update', [SubKriteriaController::class, 'update'])->name('update');
        Route::post('/delete', [SubKriteriaController::class, 'delete'])->name('delete');
    });
    Route::get('/subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');

    // Alternatif (Produk) Routes
    Route::prefix('alternatif')->name('alternatif.')->group(function () {
        Route::get('/', [AlternatifController::class, 'index'])->name('index');
        Route::post('/simpan', [AlternatifController::class, 'store'])->name('store');
        Route::get('/ubah', [AlternatifController::class, 'edit'])->name('edit');
        Route::post('/ubah', [AlternatifController::class, 'update'])->name('update');
        Route::post('/hapus', [AlternatifController::class, 'delete'])->name('delete');
    });
    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif');

    // Penilaian Routes
    Route::prefix('penilaian')->name('penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');
        Route::get('/{id}/ubah', [PenilaianController::class, 'edit'])->whereNumber('id')->name('edit');
        Route::post('/{id}/ubah', [PenilaianController::class, 'update'])->whereNumber('id')->name('update');
        Route::get('/input/{id}', [PenilaianController::class, 'inputPage'])
            ->whereNumber('id')
            ->name('input');
    });
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');

    // Permintaan Routes (Admin)
    Route::prefix('permintaan')->name('permintaan.')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])->name('index');
        Route::post('/', [PermintaanController::class, 'store'])->name('store');
        Route::put('/{id}', [PermintaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('destroy');
    });
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan');

    // SMART Routes
    Route::prefix('smart')->name('smart.')->group(function () {
        Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
        Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.store');
        Route::get('/detail-benefit-cost', [SMARTController::class, 'detailBenefitCost'])->name('detail.benefit.cost');
    });
    Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
    Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');

    // SPK Proses Route
    Route::post('/spk/proses', [KriteriaController::class, 'proses'])->name('spk.proses');
});

// Load auth routes
require __DIR__.'/auth.php';