<?php
// routes/web.php - CLEAN & WORKING

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
// PUBLIC ROUTES
// ============================================
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('public')->name('public.')->group(function () {
    Route::get('/jenis-kulit', [PublicController::class, 'jenisKulit'])->name('jenis-kulit');
    Route::get('/permintaan', [PublicController::class, 'permintaan'])->name('permintaan');
    Route::post('/permintaan', [PublicController::class, 'storePermintaan'])->name('permintaan.store');
    Route::get('/hasil-spk', [PublicController::class, 'hasilSPK'])->name('hasil-spk');
});

// PDF Public
Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');

// ============================================
// AUTHENTICATED ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-akhir', [DashboardController::class, 'hasilAkhir'])->name('hasil-akhir');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ===== KRITERIA =====
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');
    Route::get('/kriteria/create', [KriteriaController::class, 'create'])->name('kriteria.create');
    Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
    Route::get('/kriteria/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit'); // AJAX
    Route::get('/kriteria/{id}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit.page');
    Route::post('/kriteria/update', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::put('/kriteria/{id}', [KriteriaController::class, 'update'])->name('kriteria.update.put');
    Route::post('/kriteria/delete', [KriteriaController::class, 'delete'])->name('kriteria.delete');
    Route::delete('/kriteria/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
    Route::post('/kriteria/proses', [KriteriaController::class, 'proses'])->name('kriteria.proses');

    // ===== SUB-KRITERIA =====
    Route::get('/subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');
    Route::post('/subkriteria/store', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
    Route::get('/subkriteria/edit', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
    Route::post('/subkriteria/update', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
    Route::post('/subkriteria/delete', [SubKriteriaController::class, 'delete'])->name('subkriteria.delete');

    // ===== ALTERNATIF (Produk) =====
    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif');
    Route::post('/alternatif/simpan', [AlternatifController::class, 'store'])->name('alternatif.store');
    Route::get('/alternatif/ubah', [AlternatifController::class, 'edit'])->name('alternatif.edit');
    Route::post('/alternatif/ubah', [AlternatifController::class, 'update'])->name('alternatif.update');
    Route::post('/alternatif/hapus', [AlternatifController::class, 'delete'])->name('alternatif.delete');
    Route::post('/alternatif/perhitungan', [AlternatifController::class, 'perhitunganNilaiAkhir'])->name('alternatif.perhitungan');
    Route::post('/alternatif/perhitungan-metode', [AlternatifController::class, 'perhitunganMetode'])->name('alternatif.perhitungan-metode');

    // ===== PENILAIAN =====
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::get('/penilaian/{id}/ubah', [PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::post('/penilaian/{id}/ubah', [PenilaianController::class, 'update'])->name('penilaian.update');
    Route::get('/penilaian/input/{id}', [PenilaianController::class, 'inputPage'])->name('penilaian.input');
    Route::post('/penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');

    // ===== SMART/PERHITUNGAN =====
    Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
    Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');
    Route::post('/perhitungan-metode', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.metode');
    Route::get('/normalisasi-bobot', [SMARTController::class, 'indexNormalisasiBobot'])->name('normalisasi-bobot');
    Route::post('/normalisasi-bobot/hitung', [SMARTController::class, 'perhitunganNormalisasiBobot'])->name('normalisasi-bobot.hitung');
    Route::get('/nilai-utility', [SMARTController::class, 'indexNilaiUtility'])->name('nilai-utility');
    Route::post('/nilai-utility/hitung', [SMARTController::class, 'perhitunganNilaiUtility'])->name('nilai-utility.hitung');
    Route::get('/nilai-akhir', [SMARTController::class, 'indexNilaiAkhir'])->name('nilai-akhir');
    Route::post('/nilai-akhir/hitung', [SMARTController::class, 'perhitunganNilaiAkhir'])->name('nilai-akhir.hitung');
});

require __DIR__.'/auth.php';
