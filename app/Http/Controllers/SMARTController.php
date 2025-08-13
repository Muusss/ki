<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlternatifResource;
use App\Http\Resources\KriteriaResource;
use App\Http\Resources\NilaiAkhirResource;
use App\Http\Resources\NilaiUtilityResource;
use App\Http\Resources\NormalisasiBobotResource;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\NilaiUtility;
use App\Models\NormalisasiBobot;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;

class SMARTController extends Controller
{
    public function indexNormalisasiBobot()
    {
        $title = "Normalisasi Bobot";
        $normalisasiBobot = NormalisasiBobotResource::collection(
            NormalisasiBobot::with('kriteria')->orderBy('kriteria_id', 'asc')->get()
        );
        $sumBobot = Kriteria::sum('bobot_roc');
        return view('dashboard.normalisasi-bobot.index', compact('title', 'normalisasiBobot', 'sumBobot'));
    }

    public function perhitunganNormalisasiBobot()
    {
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobot = $kriteria->sum('bobot_roc');
        
        // Truncate tabel normalisasi_bobots (plural)
        NormalisasiBobot::truncate();

        foreach ($kriteria as $item) {
            NormalisasiBobot::create([
                'kriteria_id' => $item->id,
                'normalisasi' => $sumBobot > 0 ? $item->bobot_roc / $sumBobot : 0,
            ]);
        }

        return to_route('normalisasi-bobot')->with('success', 'Normalisasi Bobot Kriteria Berhasil Dilakukan');
    }

    public function indexNilaiUtility()
    {
        $title = "Nilai Utility";
        $nilaiUtility = NilaiUtilityResource::collection(
            NilaiUtility::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get()
        );
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('nis', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        return view('dashboard.nilai-utility.index', compact('title', 'nilaiUtility', 'alternatif', 'kriteria'));
    }

    public function perhitunganNilaiUtility()
    {
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $alternatif = Alternatif::orderBy('nis', 'asc')->get();
        
        // Truncate tabel nilai_utilities (plural)
        NilaiUtility::truncate();

        // Hitung nilai min-max per kriteria dari penilaian
        $nilaiMaxMin = Penilaian::query()
            ->join('kriterias as k', 'k.id', '=', 'penilaians.kriteria_id')
            ->selectRaw("penilaians.kriteria_id, k.kriteria, MAX(penilaians.nilai_asli) as nilaiMax, MIN(penilaians.nilai_asli) as nilaiMin")
            ->groupBy('penilaians.kriteria_id', 'k.kriteria')
            ->get();

        foreach ($alternatif as $item) {
            foreach ($kriteria as $value) {
                $penilaian = Penilaian::where('kriteria_id', $value->id)
                    ->where('alternatif_id', $item->id)
                    ->first();
                    
                if (!$penilaian) continue;
                
                $nilaiMax = $nilaiMaxMin->where('kriteria_id', $value->id)->first()->nilaiMax ?? 0;
                $nilaiMin = $nilaiMaxMin->where('kriteria_id', $value->id)->first()->nilaiMin ?? 0;
                $nilaiAsli = $penilaian->nilai_asli;

                // Hitung nilai utility berdasarkan atribut kriteria
                if ($nilaiMax == $nilaiMin) {
                    $nilai = 1; // Jika semua nilai sama
                } else {
                    $nilai = ($value->atribut == 'benefit')
                        ? ($nilaiAsli - $nilaiMin) / ($nilaiMax - $nilaiMin)
                        : ($nilaiMax - $nilaiAsli) / ($nilaiMax - $nilaiMin);
                }

                NilaiUtility::create([
                    'alternatif_id' => $item->id,
                    'kriteria_id' => $value->id,
                    'nilai' => $nilai,
                ]);
            }
        }

        return to_route('nilai-utility')->with('success', 'Perhitungan Nilai Utility Berhasil Dilakukan');
    }

    public function indexNilaiAkhir()
    {
        $title = "Nilai Akhir";
        $nilaiAkhir = NilaiAkhirResource::collection(
            NilaiAkhir::orderBy('alternatif_id', 'asc')->get()
        );
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('nis', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        return view('dashboard.nilai-akhir.index', compact('title', 'nilaiAkhir', 'alternatif', 'kriteria'));
    }

    public function perhitunganNilaiAkhir()
    {
        // Gunakan metode yang sudah ada di model
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();

        return to_route('nilai-akhir')->with('success', 'Perhitungan Nilai Akhir Berhasil Dilakukan');
    }

    public function indexPerhitungan()
    {
        $title = "Perhitungan Metode";

        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        $normalisasiBobot = $kriteria->map(function ($item) use ($sumBobotKriteria) {
            return (object)[
                'kriteria' => $item,
                'normalisasi' => $sumBobotKriteria > 0 ? $item->bobot_roc : 0,
            ];
        });

        $alternatif = Alternatif::orderBy('kode_produk', 'asc')->get();

        // Sisa kode sama, hapus semua referensi user/wali_kelas

        return view('dashboard.perhitungan.index', compact(
            'title',
            'normalisasiBobot',
            'nilaiUtility',
            'nilaiAkhir',
            'alternatif',
            'kriteria',
            'sumBobotKriteria',
            'hasil',
            'penilaian'
        ));
    }

    public function perhitunganMetode()
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();

        // Redirect ke halaman perhitungan dengan pesan sukses
        return redirect()->route('perhitungan')->with('success', 'Perhitungan Metode ROC + SMART Berhasil Dilakukan');
    }
}