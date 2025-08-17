<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AlternatifRequest;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AlternatifController extends Controller
{
    /**
     * Display a listing with filters
     */
    public function index(Request $request)
    {
        $query = Alternatif::query();
        
        // Apply filters
        $query->jenis($request->input('jenis_menu'))
              ->hargaKategori($request->input('harga'))
              ->search($request->input('q'));
        
        $perPage = $request->input('per_page', 15);
        $items = $query->orderBy('kode_menu', 'asc')->paginate($perPage);
        
        return response()->json([
            'data' => $items,
            'message' => 'Data retrieved successfully'
        ]);
    }

    /**
     * Store a new resource
     */
    public function store(AlternatifRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $uploadPath = public_path('img/menu');
            
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }
            
            $filename = time() . '_' . Str::slug($data['nama_menu']) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['gambar'] = $filename;
        }
        
        $alternatif = Alternatif::create($data);
        
        return response()->json([
            'data' => $alternatif,
            'message' => 'Menu created successfully'
        ], 201);
    }

    /**
     * Display the specified resource
     */
    public function show(Alternatif $alternatif)
    {
        return response()->json([
            'data' => $alternatif,
            'message' => 'Menu retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource
     */
    public function update(AlternatifRequest $request, Alternatif $alternatif)
    {
        $data = $request->validated();
        
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($alternatif->gambar && File::exists(public_path('img/menu/' . $alternatif->gambar))) {
                File::delete(public_path('img/menu/' . $alternatif->gambar));
            }
            
            $file = $request->file('gambar');
            $uploadPath = public_path('img/menu');
            $filename = time() . '_' . Str::slug($data['nama_menu']) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);
            $data['gambar'] = $filename;
        }
        
        $alternatif->update($data);
        
        return response()->json([
            'data' => $alternatif,
            'message' => 'Menu updated successfully'
        ]);
    }

    /**
     * Remove the specified resource
     */
    public function destroy(Alternatif $alternatif)
    {
        if ($alternatif->gambar && File::exists(public_path('img/menu/' . $alternatif->gambar))) {
            File::delete(public_path('img/menu/' . $alternatif->gambar));
        }
        
        $alternatif->delete();
        
        return response()->json([
            'message' => 'Menu deleted successfully'
        ]);
    }
}