<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermintaanController extends Controller
{
    public function index()
    {
        try {
            $title = 'Data Permintaan';
            
            // Perbaikan: Gunakan get() langsung, bukan collection wrapper
            $permintaan = Permintaan::orderBy('created_at', 'desc')->get();
            
            return view('dashboard.permintaan.index', compact('title', 'permintaan'));
        } catch (\Exception $e) {
            Log::error('Error in PermintaanController@index: ' . $e->getMessage());
            $permintaan = collect([]); // Empty collection sebagai fallback
            $title = 'Data Permintaan';
            return view('dashboard.permintaan.index', compact('title', 'permintaan'))
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'nama_produk' => 'required|string|max:100',
                'komposisi' => 'required|string',
                'harga' => 'required|in:<50k,50-100k,>100k',
                'spf' => 'required|in:30,35,40,50+',
            ]);

            // Set default status
            $data['status'] = 'pending';
            
            Permintaan::create($data);

            return redirect()->route('permintaan.index')
                ->with('success', 'Data permintaan berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in PermintaanController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data permintaan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $permintaan = Permintaan::findOrFail($id);
            
            $data = $request->validate([
                'nama_produk' => 'required|string|max:100',
                'komposisi' => 'required|string',
                'harga' => 'required|in:<50k,50-100k,>100k',
                'spf' => 'required|in:30,35,40,50+',
                'status' => 'sometimes|in:pending,approved,rejected',
                'admin_notes' => 'nullable|string'
            ]);

            $permintaan->update($data);

            return redirect()->route('permintaan.index')
                ->with('success', 'Data permintaan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in PermintaanController@update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data');
        }
    }

    public function destroy($id)
    {
        try {
            $permintaan = Permintaan::findOrFail($id);
            $permintaan->delete();

            return redirect()->route('permintaan.index')
                ->with('success', 'Data permintaan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in PermintaanController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus data');
        }
    }
}