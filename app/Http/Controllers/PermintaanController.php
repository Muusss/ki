<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $title = 'Data Permintaan';
        $permintaan = Permintaan::orderBy('nama_produk')->get();
        
        return view('dashboard.permintaan.index', compact('title', 'permintaan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'komposisi' => 'required|string',
            'harga' => 'required|in:<50k,50-100k,>100k',
            'spf' => 'required|in:30,35,40,50+',
        ]);

        $permintaan = Permintaan::create($data);

        return redirect()->route('permintaan.index')
            ->with('success', 'Data permintaan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $permintaan = Permintaan::findOrFail($id);
        
        $data = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'komposisi' => 'required|string',
            'harga' => 'required|in:<50k,50-100k,>100k',
            'spf' => 'required|in:30,35,40,50+',
        ]);

        $permintaan->update($data);

        return redirect()->route('permintaan.index')
            ->with('success', 'Data permintaan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->delete();

        return redirect()->route('permintaan.index')
            ->with('success', 'Data permintaan berhasil dihapus');
    }
}