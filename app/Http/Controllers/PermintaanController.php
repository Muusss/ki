<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Alternatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PermintaanController extends Controller
{
    /** List */
    public function index()
    {
        $permintaan = Permintaan::orderByDesc('created_at')->get();
        $title = 'Data Permintaan';
        return view('dashboard.permintaan.index', compact('permintaan','title'));
    }

    /** Prefill untuk modal (kembalikan JSON) */
    public function edit($id)
    {
        $p = Permintaan::findOrFail($id);
        // Kembalikan JSON agar JS bisa prefill modal
        return response()->json([
            'id'          => $p->id,
            'nama_produk' => $p->nama_produk,
            'komposisi'   => $p->komposisi,
            'harga'       => $p->harga,
            'spf'         => $p->spf,
            'status'      => $p->status,
            'admin_notes' => $p->admin_notes,
        ]);
    }

    /** Admin menyetujui: tampilkan form (di frontend) → submit ke sini untuk membuat produk + set approved */
    public function approve($id, Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:100',
            'jenis_kulit' => 'required|in:normal,berminyak,kering,kombinasi',
            'harga'       => 'required|integer|min:0',
            'spf'         => 'required|integer|min:15|max:100',
            'kode_produk' => 'nullable|string|max:30',
            'admin_notes' => 'nullable|string|max:255',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $permintaan = Permintaan::findOrFail($id);

        DB::beginTransaction();
        try {
            // 1) Simpan file ke public/img/produk dan simpan HANYA nama file di DB
            $namaFile = null;
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $namaFile = time().'_'.Str::slug($request->nama_produk, '-').'.'.$file->getClientOriginalExtension();
                $file->move(public_path('img/produk'), $namaFile);
            }

            // 2) Kode produk (pakai input kalau ada; kalau tidak, auto)
            $kode = trim((string)$request->input('kode_produk'));
            if ($kode === '') {
                $last = Alternatif::orderBy('kode_produk','desc')->first();
                if ($last && preg_match('/^PRD(\d+)/', (string)$last->kode_produk, $m)) {
                    $num = (int)$m[1] + 1;
                } else {
                    $num = 1;
                }
                $kode = 'PRD'.str_pad((string)$num, 3, '0', STR_PAD_LEFT);
            }

            // 3) Buat/ambil produk berdasar nama; selalu update kolom yang diisi admin
            $alt = Alternatif::firstOrNew(['nama_produk' => $request->nama_produk]);

            // Hanya set kode saat produk baru; jika sudah ada, biarkan kodenya
            if (! $alt->exists) {
                $alt->kode_produk = $kode;
            }

            $alt->jenis_kulit = $request->jenis_kulit;
            $alt->harga       = (int)$request->harga;
            $alt->spf         = (int)$request->spf;

            // Jika admin upload gambar, update gambarnya
            if ($namaFile) {
                // NOTE: kolom di DB kamu gunakan 'gambar'.
                // Jika kolomnya bernama 'foto', ganti baris berikut: $alt->foto = $namaFile;
                $alt->gambar = $namaFile;
            }

            $alt->save();

            // 4) Update status permintaan
            $permintaan->update([
                'status'      => 'approved',
                'admin_notes' => $request->admin_notes,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan disetujui & produk berhasil ditambahkan.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Approve permintaan gagal: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui & menambah produk: '.$e->getMessage(),
            ], 422);
        }
    }


    /** Admin menolak (alasan wajib) */
    public function reject($id, Request $request)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:255'
        ], [
            'admin_notes.required' => 'Alasan penolakan harus diisi.',
        ]);

        try {
            $p = Permintaan::findOrFail($id);
            $p->status = 'rejected';
            $p->admin_notes = $request->admin_notes;
            $p->save();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan ditolak.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak: '.$e->getMessage()
            ], 422);
        }
    }

    /** (Opsional) Update data permintaan bila masih diperlukan via modal create/edit */
    public function update(Request $request, $id)
    {
        $p = Permintaan::findOrFail($id);
        $data = $request->validate([
            'nama_produk' => 'required|string|max:100',
            'komposisi'   => 'required|string',
            'harga'       => 'required|string|max:20',
            'spf'         => 'required|string|max:10',
            'status'      => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:255',
        ]);
        $p->update($data);
        return back()->with('success','Permintaan diperbarui.');
    }

    /** Hapus */
    public function destroy($id)
    {
        $p = Permintaan::findOrFail($id);
        $p->delete();
        return back()->with('success', 'Permintaan dihapus.');
    }
}
