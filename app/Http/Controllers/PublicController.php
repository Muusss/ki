<?php
// app/Http/Controllers/PublicController.php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        // Statistik untuk homepage
        $stats = [
            'total_menu' => Alternatif::count(),
            'makanan' => Alternatif::where('jenis_menu', 'makanan')->count(),
            'minuman' => Alternatif::whereIn('jenis_menu', ['coffee', 'milkshake', 'mojito', 'yakult', 'tea'])->count(),
            'cemilan' => Alternatif::where('jenis_menu', 'cemilan')->count(),
        ];

        // Featured menu (top 3)
        $featuredMenu = NilaiAkhir::with('alternatif')
            ->orderBy('total', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('stats', 'featuredMenu'));
    }

    public function hasilSPK(Request $request)
    {
        try {
            $jenisMenu = $request->get('jenis_menu', 'all');
            $harga = $request->get('harga', 'all');
            $search = $request->get('search', '');

            // Query hasil perhitungan
            $query = NilaiAkhir::with('alternatif');
            
            if ($jenisMenu != 'all' || $harga != 'all' || $search) {
                $query->whereHas('alternatif', function($q) use ($jenisMenu, $harga, $search) {
                    if ($jenisMenu != 'all') {
                        $q->where('jenis_menu', $jenisMenu);
                    }
                    if ($harga != 'all') {
                        $q->where('harga', $harga);
                    }
                    if ($search) {
                        $q->where(function($sq) use ($search) {
                            $sq->where('nama_menu', 'like', '%'.$search.'%')
                               ->orWhere('kode_menu', 'like', '%'.$search.'%');
                        });
                    }
                });
            }

            $nilaiAkhir = $query->orderBy('total', 'desc')->get();
            
            // Add ranking
            $nilaiAkhir->each(function ($item, $index) {
                $item->peringkat_filter = $index + 1;
            });

            // Top 3 recommendations
            $topRecommendations = $nilaiAkhir->take(3);

            // Statistics
            $stats = [
                'total_menu' => Alternatif::count(),
                'total_evaluated' => NilaiAkhir::count(),
                'best_score' => NilaiAkhir::max('total') ? number_format(NilaiAkhir::max('total'), 2) : 0,
                'average_score' => NilaiAkhir::avg('total') ? number_format(NilaiAkhir::avg('total'), 2) : 0,
            ];

            // List options
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
                '<=20000' => '≤ Rp 20.000',
                '>20000-<=25000' => 'Rp 20.001 - 25.000',
                '>25000-<=30000' => 'Rp 25.001 - 30.000',
                '>30000' => '> Rp 30.000'
            ];

            return view('public.hasil-spk', compact(
                'nilaiAkhir', 
                'topRecommendations', 
                'stats',
                'jenisMenuList',
                'hargaList'
            ));
            
        } catch (\Exception $e) {
            // Handle error
            $nilaiAkhir = collect();
            $topRecommendations = collect();
            $stats = [
                'total_menu' => 0,
                'total_evaluated' => 0,
                'best_score' => 0,
                'average_score' => 0,
            ];
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
                '<=20000' => '≤ Rp 20.000',
                '>20000-<=25000' => 'Rp 20.001 - 25.000',
                '>25000-<=30000' => 'Rp 25.001 - 30.000',
                '>30000' => '> Rp 30.000'
            ];
            
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
            
            return view('public.hasil-spk', compact(
                'nilaiAkhir', 
                'topRecommendations', 
                'stats',
                'jenisMenuList',
                'hargaList',
                'error'
            ));
        }
    }

    public function jenisKulit()
    {
        return view('public.jenis-kulit');
    }

    public function menuDetail($id)
    {
        $menu = Alternatif::findOrFail($id);
        $relatedMenus = Alternatif::where('jenis_menu', $menu->jenis_menu)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('public.menu-detail', compact('menu', 'relatedMenus'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function apiRecommendations(Request $request)
    {
        $query = NilaiAkhir::with('alternatif');

        if ($request->has('jenis_menu') && $request->jenis_menu != 'all') {
            $query->whereHas('alternatif', function($q) use ($request) {
                $q->where('jenis_menu', $request->jenis_menu);
            });
        }

        $data = $query->orderBy('total', 'desc')->take(10)->get();

        return response()->json($data);
    }
}