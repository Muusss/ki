<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\NilaiAkhir;
use App\Models\Kriteria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    /**
     * Halaman Hasil SPK Menu Cafe
     */
    public function hasilSPK(Request $request)
    {
        try {
            // Filter
            $jenisMenu = $request->get('jenis_menu', 'all');
            $filterHarga = $request->get('harga', 'all');
            $search = $request->get('search', '');

            // Query dengan filter
            $query = NilaiAkhir::with(['alternatif']);

            // Filter jenis menu
            if ($jenisMenu !== 'all' && $jenisMenu !== '') {
                $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                    $q->where('jenis_menu', $jenisMenu);
                });
            }

            // Filter harga
            if ($filterHarga !== 'all' && $filterHarga !== '') {
                $query->whereHas('alternatif', function($q) use ($filterHarga) {
                    $q->where('harga', $filterHarga);
                });
            }

            // Search by nama menu
            if ($search !== '') {
                $query->whereHas('alternatif', function($q) use ($search) {
                    $q->where('nama_menu', 'LIKE', '%' . $search . '%')
                      ->orWhere('kode_menu', 'LIKE', '%' . $search . '%');
                });
            }

            $nilaiAkhir = $query->orderByDesc('total')->get();

            // Re-rank setelah filter
            $nilaiAkhir = $nilaiAkhir->map(function ($item, $index) {
                $item->peringkat_filter = $index + 1;
                return $item;
            });

            // Data untuk filter dropdown
            $jenisMenuList = [
                'makanan' => 'Makanan',
                'cemilan' => 'Cemilan',
                'coffee' => 'Coffee',
                'milkshake' => 'Milkshake',
                'mojito' => 'Mojito',
                'yakult' => 'Yakult',
                'tea' => 'Tea'
            ];

            $hargaList = [
                '<=20000' => 'Rp 20.000 ke bawah',
                '>20000-<=25000' => 'Rp 20.001 - Rp 25.000',
                '>25000-<=30000' => 'Rp 25.001 - Rp 30.000',
                '>30000' => 'Di atas Rp 30.000'
            ];

            // Get top 3 recommendations
            $topRecommendations = $nilaiAkhir->take(3);

            // Get kriteria info for display
            $kriteria = Kriteria::orderBy('urutan_prioritas')->get();

            // Statistics
            $stats = [
                'total_menu' => Alternatif::count(),
                'total_evaluated' => $nilaiAkhir->count(),
                'best_score' => $nilaiAkhir->first() ? number_format($nilaiAkhir->first()->total, 4) : 0,
                'average_score' => $nilaiAkhir->count() > 0 ? number_format($nilaiAkhir->avg('total'), 4) : 0
            ];

            return view('public.hasil-spk', compact(
                'nilaiAkhir',
                'jenisMenu',
                'filterHarga',
                'search',
                'jenisMenuList',
                'hargaList',
                'topRecommendations',
                'kriteria',
                'stats'
            ));

        } catch (\Exception $e) {
            Log::error('Error in PublicController@hasilSPK: ' . $e->getMessage());
            
            // Return empty data if error
            return view('public.hasil-spk', [
                'nilaiAkhir' => collect(),
                'jenisMenu' => 'all',
                'filterHarga' => 'all',
                'search' => '',
                'jenisMenuList' => [],
                'hargaList' => [],
                'topRecommendations' => collect(),
                'kriteria' => collect(),
                'stats' => [
                    'total_menu' => 0,
                    'total_evaluated' => 0,
                    'best_score' => 0,
                    'average_score' => 0
                ],
                'error' => 'Terjadi kesalahan saat memuat data. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Home page untuk public
     */
    public function home()
    {
        // Get featured menu (top 5)
        $featuredMenu = NilaiAkhir::with('alternatif')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Get statistics
        $stats = [
            'total_menu' => Alternatif::count(),
            'makanan' => Alternatif::where('jenis_menu', 'makanan')->count(),
            'minuman' => Alternatif::whereIn('jenis_menu', ['coffee', 'milkshake', 'mojito', 'yakult', 'tea'])->count(),
            'cemilan' => Alternatif::where('jenis_menu', 'cemilan')->count(),
        ];

        // Get latest menu
        $latestMenu = Alternatif::orderBy('created_at', 'desc')->limit(6)->get();

        return view('welcome', compact('featuredMenu', 'stats', 'latestMenu'));
    }

    /**
     * Detail menu
     */
    public function menuDetail($id)
    {
        try {
            $menu = Alternatif::findOrFail($id);
            
            // Get ranking info if available
            $nilaiAkhir = NilaiAkhir::where('alternatif_id', $id)->first();
            $ranking = null;
            
            if ($nilaiAkhir) {
                $ranking = NilaiAkhir::where('total', '>', $nilaiAkhir->total)->count() + 1;
            }

            // Get similar menu (same category)
            $similarMenu = Alternatif::where('jenis_menu', $menu->jenis_menu)
                ->where('id', '!=', $id)
                ->limit(4)
                ->get();

            return view('public.menu-detail', compact('menu', 'nilaiAkhir', 'ranking', 'similarMenu'));
            
        } catch (\Exception $e) {
            return redirect()->route('hasil-spk')->with('error', 'Menu tidak ditemukan');
        }
    }

    /**
     * About page
     */
    public function about()
    {
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        return view('public.about', compact('kriteria'));
    }

    /**
     * Export hasil to PDF (public version)
     */
    public function exportPdf(Request $request)
    {
        try {
            // Same filters as hasilSPK
            $jenisMenu = $request->get('jenis_menu', 'all');
            $filterHarga = $request->get('harga', 'all');
            
            $query = NilaiAkhir::with(['alternatif']);
            
            if ($jenisMenu !== 'all') {
                $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                    $q->where('jenis_menu', $jenisMenu);
                });
            }
            
            if ($filterHarga !== 'all') {
                $query->whereHas('alternatif', function($q) use ($filterHarga) {
                    $q->where('harga', $filterHarga);
                });
            }
            
            $nilaiAkhir = $query->orderByDesc('total')->get();
            
            
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunduh PDF');
        }
    }

    /**
     * API endpoint untuk get menu recommendations (for AJAX)
     */
    public function apiRecommendations(Request $request)
    {
        try {
            $jenisMenu = $request->get('jenis_menu', 'all');
            $limit = $request->get('limit', 10);
            
            $query = NilaiAkhir::with(['alternatif']);
            
            if ($jenisMenu !== 'all') {
                $query->whereHas('alternatif', function($q) use ($jenisMenu) {
                    $q->where('jenis_menu', $jenisMenu);
                });
            }
            
            $recommendations = $query->orderByDesc('total')
                ->limit($limit)
                ->get()
                ->map(function($item, $index) {
                    return [
                        'rank' => $index + 1,
                        'kode' => $item->alternatif->kode_menu,
                        'nama' => $item->alternatif->nama_menu,
                        'jenis' => $item->alternatif->jenis_menu,
                        'harga' => $item->alternatif->harga_label,
                        'score' => number_format($item->total, 4),
                        'image' => $item->alternatif->gambar_url
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading recommendations'
            ], 500);
        }
    }
}