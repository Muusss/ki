<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function pdf_hasil()
    {
        $judul = 'Laporan Hasil Akhir Rekomendasi Sunscreen';
        $user = Auth::user();

        // Ambil filter dari request
        $jenisKulit = request()->get('jenis_kulit', 'all');
        $hargaMin = request()->get('harga_min');
        $hargaMax = request()->get('harga_max');
        $spfMin = request()->get('spf_min');
        $spfMax = request()->get('spf_max');

        // Update judul dengan info filter
        $filterText = [];
        if ($jenisKulit !== 'all') $filterText[] = 'Jenis Kulit: ' . ucfirst($jenisKulit);
        if ($hargaMin || $hargaMax) {
            $filterText[] = 'Harga: Rp ' . number_format($hargaMin ?: 0, 0, ',', '.') . 
                        ' - Rp ' . number_format($hargaMax ?: 999999999, 0, ',', '.');
        }
        if ($spfMin || $spfMax) {
            $filterText[] = 'SPF: ' . ($spfMin ?: '15') . ' - ' . ($spfMax ?: '100');
        }
        
        if (count($filterText) > 0) {
            $judul .= ' (Filter: ' . implode(', ', $filterText) . ')';
        }

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Data alternatif dengan filter
        $alternatif = Alternatif::query()
            ->when($jenisKulit && $jenisKulit !== 'all', function($q) use ($jenisKulit) {
                $q->where('jenis_kulit', $jenisKulit);
            })
            ->orderBy('kode_produk')
            ->get();
        
        // Data penilaian dengan filter
        $tabelPenilaian = Penilaian::with(['kriteria', 'subKriteria', 'alternatif'])
            ->when($jenisKulit && $jenisKulit !== 'all', function($q) use ($jenisKulit) {
                $q->whereHas('alternatif', function($query) use ($jenisKulit) {
                    $query->where('jenis_kulit', $jenisKulit);
                });
            })
            ->get()
            ->map(function($item) {
                return (object)[
                    'alternatif' => $item->alternatif,
                    'kriteria' => $item->kriteria,
                    'sub_kriteria' => $item->subKriteria ? $item->subKriteria->label : 'Nilai: ' . $item->nilai_asli,
                    'nilai_asli' => $item->nilai_asli,
                    'nilai_normal' => $item->nilai_normal
                ];
            });
        
        // Data hasil akhir dengan ranking
        $tabelPerankingan = NilaiAkhir::query()
            ->join('alternatifs as a', 'a.id', '=', 'nilai_akhirs.alternatif_id')
            ->selectRaw("
                a.kode_produk, 
                a.nama_produk, 
                a.jenis_kulit,
                nilai_akhirs.total as nilai,
                nilai_akhirs.peringkat
            ")
            ->when($jenisKulit && $jenisKulit !== 'all', function($q) use ($jenisKulit) {
                $q->where('a.jenis_kulit', $jenisKulit);
            })
            ->orderBy('nilai', 'desc')
            ->get();
        
        // Re-rank jika ada filter
        $tabelPerankingan = $tabelPerankingan->map(function($item, $index) {
            $item->peringkat = $index + 1;
            return $item;
        });
        
        // Info tambahan
        $tanggal_cetak = now()->format('d F Y');
        $filter_info = $jenisKulit !== 'all' ? ucfirst($jenisKulit) : 'Semua Jenis';

        // Generate PDF
        $pdf = PDF::setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadview('dashboard.pdf.hasil_akhir', compact(
            'judul',
            'tanggal_cetak',
            'tabelPenilaian', 
            'tabelPerankingan',
            'kriteria',
            'alternatif',
            'filter_info',
            'user'
        ));

        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Laporan_Produk_Sunscreen_';
        if ($jenisKulit !== 'all') {
            $filename .= ucfirst($jenisKulit) . '_';
        }
        $filename .= date('Y-m-d') . '.pdf';
        
        return $pdf->stream($filename);
    }
}