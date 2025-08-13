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
use Illuminate\Http\Request;
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
        $user = Auth::user();
        
        // Gunakan metode yang sudah ada di model
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART(null, $user);
        NilaiAkhir::hitungTotal(null, $user);

        return to_route('nilai-akhir')->with('success', 'Perhitungan Nilai Akhir Berhasil Dilakukan');
    }
    public function indexPerhitungan()
    {
        $title = "Perhitungan Metode ROC + SMART";

        // Data kriteria dengan bobot ROC
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        $normalisasiBobot = $kriteria->map(function ($item) use ($sumBobotKriteria) {
            return (object)[
                'kriteria'    => $item,
                'normalisasi' => $sumBobotKriteria > 0 ? $item->bobot_roc : 0,
            ];
        });

        // Data alternatif
        $alternatif = Alternatif::orderBy('kode_produk', 'asc')->get();

        $altIds  = $alternatif->pluck('id')->all();
        $kritIds = $kriteria->pluck('id')->all();

        $penilaianAll = Penilaian::whereIn('alternatif_id', $altIds)
            ->whereIn('kriteria_id', $kritIds)
            ->get()
            ->keyBy(fn ($p) => $p->alternatif_id.'-'.$p->kriteria_id);

        // Kirim juga ke view sebagai $penilaian (Collection biasa, bukan keyed),
        // karena di tabel "Normalisasi Matriks" kamu pakai $penilaian->where(...)
        $penilaian = $penilaianAll->values();

        $nilaiUtility = collect();
        foreach ($alternatif as $alt) {
            foreach ($kriteria as $krit) {
                $key = $alt->id.'-'.$krit->id;
                $p   = $penilaianAll->get($key);

                $nilaiUtility->push((object)[
                    'alternatif_id' => $alt->id,
                    'kriteria_id'   => $krit->id,
                    'nilai'         => $p?->nilai_normal ?? 0,
                ]);
            }
        }

        $nilaiAkhir = collect();
        foreach ($alternatif as $alt) {
            foreach ($kriteria as $krit) {
                $key   = $alt->id.'-'.$krit->id;
                $p     = $penilaianAll->get($key);
                $nNorm = $p?->nilai_normal;

                $nilai = ($nNorm !== null)
                    ? ((float)$nNorm * (float)$krit->bobot_roc)
                    : 0.0;

                $nilaiAkhir->push((object)[
                    'alternatif_id' => (int)$alt->id,
                    'kriteria_id'   => (int)$krit->id,
                    'nilai'         => (float)$nilai,
                ]);
            }
        }

        // Data hasil akhir
        $hasil = NilaiAkhir::with('alternatif')
            ->orderByDesc('total')
            ->get();


        return view('dashboard.perhitungan.index', compact(
            'title',
            'normalisasiBobot',
            'nilaiUtility',
            'nilaiAkhir',
            'alternatif',
            'kriteria',
            'sumBobotKriteria',
            'hasil',
            'penilaian' // <— penting: dikirim ke view
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

    // TAMBAHAN: Method untuk detail benefit vs cost
    public function detailBenefitCost()
    {
        $title = "Detail Perhitungan Benefit vs Cost";
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        
        return view('dashboard.perhitungan.detail', compact('title', 'kriteria'));
    }
}