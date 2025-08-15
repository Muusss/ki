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

        // Build judul dengan info filter
        $filterText = [];
        if ($jenisKulit !== 'all') {
            $filterText[] = 'Jenis Kulit: ' . ucfirst($jenisKulit);
        }
        if ($hargaMin || $hargaMax) {
            $filterText[] = 'Harga: Rp ' . number_format($hargaMin ?: 0, 0, ',', '.') . 
                        ' - Rp ' . number_format($hargaMax ?: 999999999, 0, ',', '.');
        }
        if ($spfMin || $spfMax) {
            $filterText[] = 'SPF: ' . ($spfMin ?: '15') . ' - ' . ($spfMax ?: '100');
        }
        
        if (count($filterText) > 0) {
            $judul .= ' (' . implode(', ', $filterText) . ')';
        }

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Query dengan filter untuk hasil akhir
        $query = NilaiAkhir::with('alternatif');
        
        // Filter jenis kulit
        if ($jenisKulit && $jenisKulit !== 'all') {
            $query->whereHas('alternatif', function($q) use ($jenisKulit) {
                $q->where('jenis_kulit', $jenisKulit);
            });
        }
        
        // Filter harga
        if ($hargaMin !== null && $hargaMin !== '') {
            $query->whereHas('alternatif', function($q) use ($hargaMin) {
                $q->where('harga', '>=', $hargaMin);
            });
        }
        if ($hargaMax !== null && $hargaMax !== '') {
            $query->whereHas('alternatif', function($q) use ($hargaMax) {
                $q->where('harga', '<=', $hargaMax);
            });
        }
        
        // Filter SPF
        if ($spfMin !== null && $spfMin !== '') {
            $query->whereHas('alternatif', function($q) use ($spfMin) {
                $q->where('spf', '>=', $spfMin);
            });
        }
        if ($spfMax !== null && $spfMax !== '') {
            $query->whereHas('alternatif', function($q) use ($spfMax) {
                $q->where('spf', '<=', $spfMax);
            });
        }
        
        // Get data yang sudah difilter
        $nilaiAkhir = $query->orderByDesc('total')->get();
        
        // Re-rank setelah filter
        $tabelPerankingan = $nilaiAkhir->map(function($item, $index) {
            $item->peringkat = $index + 1;
            
            // Konversi gambar ke base64 untuk PDF
            $alt = $item->alternatif;
            if ($alt && $alt->gambar) {
                $imagePath = public_path('img/produk/' . $alt->gambar);
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
                    $item->image_base64 = 'data:image/' . $imageType . ';base64,' . $imageData;
                } else {
                    $item->image_base64 = null;
                }
            } else {
                $item->image_base64 = null;
            }
            
            return $item;
        });
        
        // Data alternatif yang sesuai filter
        $alternatifIds = $nilaiAkhir->pluck('alternatif_id');
        $alternatif = Alternatif::whereIn('id', $alternatifIds)->get();
        
        // Data penilaian yang sesuai filter
        $tabelPenilaian = Penilaian::with(['kriteria', 'subKriteria', 'alternatif'])
            ->whereIn('alternatif_id', $alternatifIds)
            ->get();
        
        // Info tambahan
        $tanggal_cetak = now()->format('d F Y');
        $filter_info = $jenisKulit !== 'all' ? ucfirst($jenisKulit) : 'Semua Jenis';

        // Generate PDF dengan option untuk image
        $pdf = PDF::setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'enable_remote' => true
        ])->loadview('dashboard.pdf.hasil_akhir', compact(
            'judul',
            'tanggal_cetak',
            'tabelPenilaian', 
            'tabelPerankingan',
            'kriteria',
            'alternatif',
            'filter_info',
            'user',
            'jenisKulit',
            'hargaMin',
            'hargaMax',
            'spfMin',
            'spfMax'
        ));

        $pdf->setPaper('A4', 'portrait');
        
        // Nama file dengan filter info
        $filename = 'Laporan_Sunscreen_';
        if ($jenisKulit !== 'all') {
            $filename .= ucfirst($jenisKulit) . '_';
        }
        if ($hargaMin || $hargaMax) {
            $filename .= 'Harga_' . ($hargaMin ?: '0') . '-' . ($hargaMax ?: 'max') . '_';
        }
        if ($spfMin || $spfMax) {
            $filename .= 'SPF_' . ($spfMin ?: 'min') . '-' . ($spfMax ?: 'max') . '_';
        }
        $filename .= date('Y-m-d') . '.pdf';
        
        return $pdf->stream($filename);
    }
}