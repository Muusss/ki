<?php
// routes/web.php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SMARTController;
use App\Http\Controllers\SubKriteriaController;
use Illuminate\Support\Facades\Route;

// ============================================
// PUBLIC ROUTES (TIDAK PERLU LOGIN)
// ============================================
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/hasil-spk', [PublicController::class, 'hasilSPK'])->name('hasil-spk');
Route::get('/jenis-kulit', [PublicController::class, 'jenisKulit'])->name('public.jenis-kulit');
Route::get('/menu/{id}', [PublicController::class, 'menuDetail'])->name('menu.detail');
Route::get('/about', [PublicController::class, 'about'])->name('about');

// API route untuk recommendations (jika diperlukan)
Route::get('/api/recommendations', [PublicController::class, 'apiRecommendations'])->name('api.recommendations');

// ============================================
// AUTHENTICATED ROUTES (PERLU LOGIN)
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
    Route::get('/kriteria/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::post('/kriteria/update', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::post('/kriteria/delete', [KriteriaController::class, 'delete'])->name('kriteria.delete');
    Route::post('/kriteria/proses', [KriteriaController::class, 'proses'])->name('kriteria.proses');

    // ===== SUB-KRITERIA =====
    Route::get('/subkriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');
    Route::post('/subkriteria/store', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
    Route::get('/subkriteria/edit', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
    Route::post('/subkriteria/update', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
    Route::post('/subkriteria/delete', [SubKriteriaController::class, 'delete'])->name('subkriteria.delete');

    // ===== ALTERNATIF (Menu) =====
    Route::get('/alternatif', [AlternatifController::class, 'index'])->name('alternatif');
    Route::post('/alternatif/simpan', [AlternatifController::class, 'store'])->name('alternatif.store');
    Route::get('/alternatif/ubah', [AlternatifController::class, 'edit'])->name('alternatif.edit');
    Route::post('/alternatif/ubah', [AlternatifController::class, 'update'])->name('alternatif.update');
    Route::post('/alternatif/hapus', [AlternatifController::class, 'delete'])->name('alternatif.delete');

    // ===== PENILAIAN =====
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian');
    Route::get('/penilaian/{id}/ubah', [PenilaianController::class, 'edit'])->name('penilaian.edit');
    Route::post('/penilaian/{id}/ubah', [PenilaianController::class, 'update'])->name('penilaian.update');
    Route::get('/penilaian/input/{id}', [PenilaianController::class, 'inputPage'])->name('penilaian.input');

    // ===== PERHITUNGAN =====
    Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
    Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');
    Route::get('/detail-benefit-cost', [SMARTController::class, 'detailBenefitCost'])->name('smart.detail.benefit.cost');

    // Additional SMART routes if needed
    Route::get('/normalisasi-bobot', [SMARTController::class, 'indexNormalisasiBobot'])->name('normalisasi-bobot');
    Route::post('/normalisasi-bobot', [SMARTController::class, 'perhitunganNormalisasiBobot'])->name('normalisasi-bobot.hitung');
    Route::get('/nilai-utility', [SMARTController::class, 'indexNilaiUtility'])->name('nilai-utility');
    Route::post('/nilai-utility', [SMARTController::class, 'perhitunganNilaiUtility'])->name('nilai-utility.hitung');
    Route::get('/nilai-akhir', [SMARTController::class, 'indexNilaiAkhir'])->name('nilai-akhir');
    Route::post('/nilai-akhir', [SMARTController::class, 'perhitunganNilaiAkhir'])->name('nilai-akhir.hitung');

    // ===== PDF Export =====
    Route::get('/pdf/hasil-akhir', [PDFController::class, 'hasilAkhir'])->name('pdf.hasilAkhir');
});

// Include authentication routes
require __DIR__.'/auth.php';