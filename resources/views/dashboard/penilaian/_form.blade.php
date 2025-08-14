{{-- resources/views/dashboard/penilaian/_form.blade.php --}}
<form id="formPenilaian" data-alternatif-id="{{ $alternatif->id }}">
    @csrf

    {{-- Hidden (kalau butuh kirim ke backend) --}}
    <input type="hidden" name="alternatif_id" value="{{ $alternatif->id }}">

    @php
        // Badge jenis kulit
        $skin = strtolower($alternatif->jenis_kulit ?? '');
        $skinLabel = $alternatif->jenis_kulit ? ucfirst($alternatif->jenis_kulit) : '-';
        $skinClass = match ($skin) {
            'normal'    => 'success',
            'berminyak' => 'warning',
            'kering'    => 'info',
            'kombinasi' => 'secondary',
            default     => 'secondary',
        };
    @endphp

    <div class="mb-2">
        <strong>{{ $alternatif->kode_produk }} - {{ $alternatif->nama_produk }}</strong><br>
        <span class="badge bg-{{ $skinClass }}">
            <i class="bi bi-droplet-fill me-1"></i>{{ $skinLabel }}
        </span>
    </div>

    {{-- Alert notifikasi --}}
    <div id="alertNotif" class="alert alert-success d-none" role="alert">
        <i class="bi bi-check-circle"></i> <span id="alertMessage">Data berhasil disimpan!</span>
    </div>

    <table class="table table-bordered align-middle">
        <thead>
            <tr class="table-light">
                <th style="width:70px" class="text-center">Kode</th>
                <th>Kriteria</th>
                <th style="width:110px" class="text-center">Nilai (1–4)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kriteria as $k)
            @php
                // nilai yang sudah ada
                $row = collect(data_get($rows, $k->id, []))->first();

                // daftar subkriteria utk kriteria ini (urut skor)
                $subKriterias = \App\Models\SubKriteria::where('kriteria_id', $k->id)
                    ->orderBy('skor', 'asc')
                    ->get();
            @endphp
            <tr>
                <td class="text-center">
                    <span class="badge text-bg-secondary">{{ $k->kode }}</span>
                </td>
                <td>{{ $k->kriteria }}</td>
                <td>
                    <input
                        type="number" inputmode="numeric"
                        step="1" min="1" max="4"
                        class="form-control nilai-input text-center @error('nilai_asli.'.$k->id) is-invalid @enderror"
                        name="nilai_asli[{{ $k->id }}]"
                        data-kriteria-id="{{ $k->id }}"
                        value="{{ old('nilai_asli.'.$k->id, $row->nilai_asli ?? '') }}"
                        placeholder="1–4"
                        onchange="updateKeterangan(this, {{ $k->id }})"
                    >
                    @error('nilai_asli.'.$k->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback"></div>
                    @enderror
                </td>
                <td>
                    <div id="keterangan-{{ $k->id }}" class="small">
                        @if($subKriterias->isNotEmpty())
                            <div class="subkriteria-info">
                                @foreach($subKriterias as $sub)
                                    <div class="d-flex align-items-center mb-1 subkriteria-item" data-skor="{{ $sub->skor }}">
                                        <span class="badge bg-secondary me-2" style="width:26px;">{{ $sub->skor }}</span>
                                        <span class="text-muted">
                                            {{ $sub->label }}
                                            @if(!is_null($sub->min_val) && !is_null($sub->max_val))
                                                ({{ $sub->min_val }} – {{ $sub->max_val }})
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">Tidak ada subkriteria</span>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="alert alert-info">
        <small>
            <i class="bi bi-lightbulb"></i> <strong>Tips:</strong>
            <ul class="mb-0 mt-1">
                <li>Gunakan <kbd>Tab</kbd> untuk pindah kolom, <kbd>Shift+Tab</kbd> kembali</li>
                <li>Tekan <kbd>Enter</kbd> atau <kbd>Ctrl+S</kbd> untuk menyimpan</li>
                <li>Nilai otomatis tersimpan saat berpindah field</li>
                <li>Keterangan di kanan akan tersorot sesuai angka 1–4</li>
            </ul>
        </small>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <button type="button" class="btn btn-secondary" id="btnPrevProduk">
                <i class="bi bi-chevron-left"></i> Produk Sebelumnya
            </button>
            <button type="button" class="btn btn-secondary" id="btnNextProduk">
                Produk Berikutnya <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="bi bi-save"></i> Simpan
            </button>
            <button type="button" class="btn btn-success" id="btnSimpanLanjut">
                <i class="bi bi-save"></i> Simpan & Lanjut
            </button>
        </div>
    </div>
</form>

<style>
.subkriteria-item{transition:.3s;padding:2px 5px;border-radius:4px}
.subkriteria-item.active{background:#e7f3ff;font-weight:600}
.subkriteria-item.active .badge{background:#0d6efd!important}
.subkriteria-item.active .text-muted{color:#0d6efd!important}
.nilai-input:focus{box-shadow:0 0 0 .2rem rgba(13,110,253,.25)}
kbd{padding:2px 4px;font-size:90%;color:#fff;background:#333;border-radius:3px}
</style>

<script>
// highlight baris subkriteria sesuai input
function updateKeterangan(input, kriteriaId){
    const nilai = parseInt(input.value);
    const wrap = document.getElementById('keterangan-'+kriteriaId);
    if(!wrap) return;
    wrap.querySelectorAll('.subkriteria-item').forEach(el=>el.classList.remove('active'));
    if(!Number.isNaN(nilai) && nilai>=1 && nilai<=4){
        const hit = wrap.querySelector('.subkriteria-item[data-skor="'+nilai+'"]');
        if(hit) hit.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function(){
    // init highlight untuk nilai yang sudah ada
    document.querySelectorAll('.nilai-input').forEach(inp=>{
        if (inp.value) updateKeterangan(inp, inp.dataset.kriteriaId);
    });
});

// jQuery helpers
if (window.jQuery) {
    $.ajaxSetup({ headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    $(function(){
        let autoSaveTimer;

        // Auto save ketika nilai berubah
        $('.nilai-input').on('change', function(){
            clearTimeout(autoSaveTimer);
            const $in = $(this);
            const v = parseInt($in.val());
            if ((v<1 || v>4 || isNaN(v)) && $in.val()!==''){
                $in.addClass('is-invalid');
                return;
            }
            $in.removeClass('is-invalid');

            autoSaveTimer = setTimeout(()=>{ simpanDataAjax(false); }, 800);
        });

        // Submit form
        $('#formPenilaian').on('submit', function(e){
            e.preventDefault();
            simpanDataAjax(false);
        });

        // Simpan & Lanjut
        $('#btnSimpanLanjut').on('click', function(){ simpanDataAjax(true); });

        // Navigasi produk
        $('#btnPrevProduk').on('click', function(){ navigasiAlternatif('prev'); });
        $('#btnNextProduk').on('click', function(){ navigasiAlternatif('next'); });

        // Keyboard helper
        $('.nilai-input').on('keydown', function(e){
            const inputs = $('.nilai-input');
            const idx = inputs.index(this);

            if (e.ctrlKey && (e.key==='s' || e.key==='S')) { e.preventDefault(); simpanDataAjax(false); return; }
            if (e.key === 'Enter'){ e.preventDefault(); simpanDataAjax(false); return; }
            if (e.key === 'Tab'){
                e.preventDefault();
                const next = e.shiftKey ? idx-1 : idx+1;
                if(next>=0 && next<inputs.length) inputs.eq(next).focus().select();
                return;
            }
            if (e.key>='1' && e.key<='4'){
                e.preventDefault();
                $(this).val(e.key).trigger('change');
                if (idx < inputs.length - 1) {
                    setTimeout(()=>inputs.eq(idx+1).focus().select(), 100);
                }
            }
        });

        // Fokus awal
        const firstEmpty = $('.nilai-input').filter(function(){ return $(this).val()===''; }).first();
        (firstEmpty.length ? firstEmpty : $('.nilai-input').first()).focus();

        // === AJAX save ===
        function simpanDataAjax(lanjutNext){
            const $form = $('#formPenilaian');
            const alternatifId = $form.data('alternatif-id');
            const formData = $form.serialize();

            $('#btnSimpan, #btnSimpanLanjut').prop('disabled', true);
            $('#btnSimpan').html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

            $.post('{{ url("penilaian") }}/'+ alternatifId +'/ubah', formData)
                .done(function(){
                    // success alert
                    $('#alertNotif').removeClass('d-none alert-danger').addClass('alert-success');
                    $('#alertMessage').text('Data berhasil disimpan!');
                    setTimeout(()=>$('#alertNotif').addClass('d-none'), 1800);

                    // Kirim nilai terbaru ke tabel induk (opsional)
                    const values = {};
                    $('.nilai-input').each(function(){
                        const id = $(this).data('kriteria-id'); const vv = $(this).val();
                        if (vv !== '') values[id] = vv;
                    });
                    if (window.parent && window.parent.updatePenilaianRow) {
                        window.parent.updatePenilaianRow(alternatifId, values);
                    }

                    if (lanjutNext){
                        setTimeout(()=>{ navigasiAlternatif('next'); }, 400);
                    }
                })
                .fail(function(xhr){
                    $('#alertNotif').removeClass('d-none alert-success').addClass('alert-danger');
                    const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan data. Silakan coba lagi.';
                    $('#alertMessage').text(msg);
                })
                .always(function(){
                    $('#btnSimpan, #btnSimpanLanjut').prop('disabled', false);
                    $('#btnSimpan').html('<i class="bi bi-save"></i> Simpan');
                });
        }

        // panggil fungsi navigasi di parent
        function navigasiAlternatif(direction){
            if (window.parent && window.parent.navigasiAlternatif) {
                window.parent.navigasiAlternatif(direction);
            }
        }
    });
}
</script>
