<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlternatifController extends Controller
{
    /**
     * Display all data without pagination
     */
    public function index()
    {
        // Ambil semua data tanpa pagination, urutkan berdasarkan created_at terbaru
        $items = Alternatif::orderBy('created_at', 'desc')->get();
        
        return view('dashboard.alternatif.index', compact('items'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_menu' => 'required|unique:alternatifs,kode_menu',
            'nama_menu' => 'required',
            'jenis_menu' => 'required',
            'harga' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        $data = $request->only(['kode_menu', 'nama_menu', 'jenis_menu', 'harga']);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/menu'), $imageName);
            $data['gambar'] = $imageName;
        }

        Alternatif::create($data);

        return redirect()->route('alternatif')->with('success', 'Menu berhasil ditambahkan');
    }

    /**
     * Get data for editing
     */
    public function edit(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->alternatif_id);
        return response()->json($alternatif);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->id);

        $request->validate([
            'kode_menu' => 'required|unique:alternatifs,kode_menu,' . $alternatif->id,
            'nama_menu' => 'required',
            'jenis_menu' => 'required',
            'harga' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        $data = $request->only(['kode_menu', 'nama_menu', 'jenis_menu', 'harga']);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($alternatif->gambar && file_exists(public_path('img/menu/' . $alternatif->gambar))) {
                unlink(public_path('img/menu/' . $alternatif->gambar));
            }

            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/menu'), $imageName);
            $data['gambar'] = $imageName;
        }

        $alternatif->update($data);

        return redirect()->route('alternatif')->with('success', 'Menu berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage
     */
    public function delete(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->id);

        // Delete image if exists
        if ($alternatif->gambar && file_exists(public_path('img/menu/' . $alternatif->gambar))) {
            unlink(public_path('img/menu/' . $alternatif->gambar));
        }

        // Delete related penilaians if exists
        $alternatif->penilaians()->delete();
        
        // Delete the alternatif
        $alternatif->delete();

        return redirect()->route('alternatif')->with('success', 'Menu berhasil dihapus');
    }
}