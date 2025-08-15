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
    return view('welcome');
});
// Route root - redirect ke login
Route::get('/login', function () {
    return redirect()->route('login');
});

// Semua route memerlukan autentikasi
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

    // PDF Routes
    Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');

    // Kriteria Routes
    Route::prefix('kriteria')->name('kriteria.')->group(function () {
        Route::get('/', [KriteriaController::class, 'index'])->name('index');
        Route::post('/store', [KriteriaController::class, 'store'])->name('store');
        Route::get('/edit', [KriteriaController::class, 'edit'])->name('edit');
        Route::post('/update', [KriteriaController::class, 'update'])->name('update');
        Route::post('/delete', [KriteriaController::class, 'delete'])->name('delete');
        Route::post('/proses', [KriteriaController::class, 'proses'])->name('proses');
    });
    // Shortcut
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');

    // Sub-Kriteria Routes
    Route::prefix('sub-kriteria')->name('subkriteria.')->group(function () {
        Route::get('/', [SubKriteriaController::class, 'index'])->name('index');
        Route::post('/store', [SubKriteriaController::class, 'store'])->name('store');
        Route::get('/edit', [SubKriteriaController::class, 'edit'])->name('edit');
        Route::post('/update', [SubKriteriaController::class, 'update'])->name('update');
        Route::post('/delete', [SubKriteriaController::class, 'delete'])->name('delete');
    });
    // Shortcut
    Route::get('/subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');

    // Alternatif (Produk) Routes
    Route::prefix('alternatif')->name('alternatif.')->group(function () {
        Route::get('/', [AlternatifController::class, 'index'])->name('index');
        Route::post('/simpan', [AlternatifController::class, 'store'])->name('store');
        Route::get('/ubah', [AlternatifController::class, 'edit'])->name('edit');      // expects ?alternatif_id=...
        Route::post('/ubah', [AlternatifController::class, 'update'])->name('update');
        Route::post('/hapus', [AlternatifController::class, 'delete'])->name('delete');
    });
    // Shortcut
    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif');

    // Penilaian Routes (mendukung query ?skin=... sebagai filter server-side)
    Route::prefix('penilaian')->name('penilaian.')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('index');
        Route::get('/{id}/ubah', [PenilaianController::class, 'edit'])->whereNumber('id')->name('edit');
        Route::post('/{id}/ubah', [PenilaianController::class, 'update'])->whereNumber('id')->name('update');

        // HALAMAN BARU: input full page
        Route::get('/input/{id}', [PenilaianController::class, 'inputPage'])
            ->whereNumber('id')
            ->name('input');
    });
    // Shortcut (digunakan oleh view/JS sebagai route('penilaian'))
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');

    // Permintaan Routes
    Route::prefix('permintaan')->name('permintaan.')->group(function () {
        Route::get('/', [PermintaanController::class, 'index'])->name('index');
        Route::post('/', [PermintaanController::class, 'store'])->name('store');
        Route::put('/{id}', [PermintaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermintaanController::class, 'destroy'])->name('destroy');
    });
    // Shortcut
    Route::get('/permintaan', [PermintaanController::class, 'index'])->name('permintaan');

    // SMART Routes
    Route::prefix('smart')->name('smart.')->group(function () {
        Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
        Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.store');
        Route::get('/detail-benefit-cost', [SMARTController::class, 'detailBenefitCost'])->name('detail.benefit.cost');
    });
    // Shortcut perhitungan
    Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
    Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');

    // SPK Proses Route
    Route::post('/spk/proses', [KriteriaController::class, 'proses'])->name('spk.proses');
});

// Load auth routes
require __DIR__.'/auth.php';
