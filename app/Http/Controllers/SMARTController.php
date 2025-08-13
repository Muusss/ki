<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class SMARTController extends Controller
{
    public function indexPerhitungan()
    {
        $title = "Perhitungan Metode ROC + SMART";

        // Data kriteria dengan bobot ROC
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        // Data alternatif
        $alternatif = Alternatif::orderBy('kode_produk', 'asc')->get();

        // Data penilaian
        $penilaian = Penilaian::with(['alternatif', 'kriteria'])->get();

        // Data hasil akhir
        $hasil = NilaiAkhir::with('alternatif')
            ->orderByDesc('total')
            ->get();

        // Info normalisasi untuk setiap kriteria
        $infoNormalisasi = Penilaian::getInfoNormalisasi();

        // Data untuk nilai utility (placeholder - sesuaikan dengan kebutuhan)
        $nilaiUtility = collect();

        // Data untuk nilai akhir (placeholder - sesuaikan dengan kebutuhan)
        $nilaiAkhir = collect();

        return view('dashboard.perhitungan.index', compact(
            'title',
            'kriteria',
            'sumBobotKriteria',
            'alternatif',
            'penilaian',
            'hasil',
            'nilaiUtility',
            'nilaiAkhir',
            'infoNormalisasi'
        ));
    }

    public function perhitunganMetode(Request $request)
    {
        try {
            // Lakukan perhitungan ROC
            Kriteria::hitungROC();
            
            // Lakukan normalisasi SMART
            Penilaian::normalisasiSMART();
            
            // Hitung nilai akhir
            NilaiAkhir::hitungTotal();

            return redirect()->route('perhitungan')
                ->with('success', 'Perhitungan ROC + SMART berhasil dilakukan!');
                
        } catch (\Exception $e) {
            return redirect()->route('perhitungan')
                ->with('error', 'Terjadi kesalahan dalam perhitungan: ' . $e->getMessage());
        }
    }
}