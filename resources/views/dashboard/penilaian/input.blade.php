@extends('dashboard.layouts.main')

@section('content')
@php
    $skinVal   = strtolower($alternatif->jenis_kulit ?? '');
    $skinLabel = $alternatif->jenis_kulit ? ucfirst($alternatif->jenis_kulit) : '-';
    $skinClass = match ($skinVal) {
        'normal' => 'success', 'berminyak' => 'warning',
        'kering' => 'info', 'kombinasi' => 'secondary',
        default => 'secondary',
    };
    $backUrl = $skin ? route('penilaian', ['skin' => $skin]) : route('penilaian');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div class="d-flex align-items-center gap-3">
        <div>
            <h3 class="mb-1">Input Penilaian</h3>
            <div class="text-muted small">Masukkan nilai 1–4 untuk setiap kriteria</div>
        </div>
        <div class="vr d-none d-md-block"></div>
        <div>
            <div class="fw-semibold">{{ $alternatif->kode_produk }} — {{ $alternatif->nama_produk }}</div>
            <span class="badge bg-{{ $skinClass }}"><i class="bi bi-droplet-fill me-1"></i>{{ $skinLabel }}</span>
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ $backUrl }}" class="btn btn-light">
            <i class="bi bi-arrow-left"></i> Kembali ke Penilaian
        </a>
        @if($prevId)
            <a class="btn btn-outline-secondary" href="{{ route('penilaian.input', ['id'=>$prevId, 'skin'=>$skin]) }}">
                <i class="bi bi-chevron-left"></i> Sebelumnya
            </a>
        @endif
        @if($nextId)
            <a class="btn btn-outline-secondary" href="{{ route('penilaian.input', ['id'=>$nextId, 'skin'=>$skin]) }}">
                Berikutnya <i class="bi bi-chevron-right"></i>
            </a>
        @endif
    </div>
</div>

{{-- Notifikasi --}}
<div id="alertNotif" class="alert d-none" role="alert">
    <i class="bi bi-info-circle"></i> <span id="alertMessage"></span>
</div>

<div class="card">
    <div class="card-body">
        <form id="formPenilaian" data-alternatif-id="{{ $alternatif->id }}" method="POST" action="{{ route('penilaian.update', ['id'=>$alternatif->id]) }}">
            @csrf

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr class="table-light">
                            <th style="width:80px" class="text-center">Kode</th>
                            <th>Kriteria</th>
                            <th style="width:120px" class="text-center">Nilai (1–4)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kriteria as $k)
                            @php
                                $row = collect(data_get($rows, $k->id, []))->first();
                                $subKriterias = \App\Models\SubKriteria::where('kriteria_id', $k->id)->orderBy('skor','asc')->get();
                            @endphp
                            <tr>
                                <td class="text-center"><span class="badge text-bg-secondary">{{ $k->kode }}</span></td>
                                <td class="fw-semibold">{{ $k->kriteria }}</td>
                                <td>
                                    <input
                                        type="number" inputmode="numeric" step="1" min="1" max="4"
                                        class="form-control nilai-input text-center @error('nilai_asli.'.$k->id) is-invalid @enderror"
                                        name="nilai_asli[{{ $k->id }}]"
                                        data-kriteria-id="{{ $k->id }}"
                                        value="{{ old('nilai_asli.'.$k->id, $row->nilai_asli ?? '') }}"
                                        placeholder="1–4"
                                    >
                                    @error('nilai_asli.'.$k->id)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback"></div>
                                    @enderror
                                </td>
                                <td>
                                    <div id="keterangan-{{ $k->id }}" class="small">
                                        @forelse($subKriterias as $sub)
                                            <div class="d-flex align-items-center mb-1 subkriteria-item" data-skor="{{ $sub->skor }}">
                                                <span class="badge bg-secondary me-2" style="width:26px;">{{ $sub->skor }}</span>
                                                <span class="text-muted">
                                                    {{ $sub->label }}
                                                    @if(!is_null($sub->min_val) && !is_null($sub->max_val))
                                                        ({{ $sub->min_val }} – {{ $sub->max_val }})
                                                    @endif
                                                </span>
                                            </div>
                                        @empty
                                            <span class="text-muted">Tidak ada subkriteria</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-3">
                <div class="text-muted small">
                    Tips: <kbd>Tab</kbd> / <kbd>Shift+Tab</kbd> untuk navigasi, <kbd>Enter</kbd> atau <kbd>Ctrl+S</kbd> untuk simpan, sistem juga auto‑save saat nilai berubah.
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ $backUrl }}" class="btn btn-light">Tutup</a>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                    @if($nextId)
                    <button type="button" class="btn btn-success" id="btnSimpanLanjut">
                        <i class="bi bi-save"></i> Simpan & Lanjut
                    </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<style>
.subkriteria-item{transition:.25s;padding:2px 6px;border-radius:6px}
.subkriteria-item.active{background:#e7f3ff;font-weight:600}
.subkriteria-item.active .badge{background:#0d6efd!important}
.subkriteria-item.active .text-muted{color:#0d6efd!important}
.nilai-input:focus{box-shadow:0 0 0 .2rem rgba(13,110,253,.25)}
kbd{padding:2px 4px;font-size:90%;color:#fff;background:#333;border-radius:3px}
</style>
@endsection

@section('js')
<script>
if (window.jQuery) {
    $.ajaxSetup({ headers: { 'X-Requested-With': 'XMLHttpRequest' } });
}

function highlightFor(kriteriaId, value){
    const wrap = document.getElementById('keterangan-'+kriteriaId);
    if(!wrap) return;
    wrap.querySelectorAll('.subkriteria-item').forEach(el=>el.classList.remove('active'));
    const v = parseInt(value);
    if(!Number.isNaN(v) && v>=1 && v<=4){
        const el = wrap.querySelector('.subkriteria-item[data-skor="'+v+'"]');
        if(el) el.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.nilai-input').forEach(inp=>{
        if (inp.value) highlightFor(inp.dataset.kriteriaId, inp.value);
    });
});

$(function(){
    let autoSaveTimer;

    function showNotif(type, text){
        const $a = $('#alertNotif');
        $a.removeClass('d-none alert-success alert-danger').addClass(type==='ok'?'alert-success':'alert-danger');
        $('#alertMessage').text(text);
        setTimeout(()=> $a.addClass('d-none'), 1800);
    }

    $('.nilai-input').on('input change', function(){
        const $in = $(this);
        const v = parseInt($in.val());
        highlightFor($in.data('kriteria-id'), v);
        if ((v<1 || v>4 || isNaN(v)) && $in.val()!==''){
            $in.addClass('is-invalid'); return;
        }
        $in.removeClass('is-invalid');
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(()=> submitAjax(false), 700);
    });

    // keyboard shortcuts
    $('.nilai-input').on('keydown', function(e){
        const $inputs = $('.nilai-input');
        const idx = $inputs.index(this);
        if (e.ctrlKey && (e.key==='s' || e.key==='S')) { e.preventDefault(); submitAjax(false); return; }
        if (e.key==='Enter'){ e.preventDefault(); submitAjax(false); return; }
        if (e.key==='Tab'){
            e.preventDefault();
            const next = e.shiftKey ? idx-1 : idx+1;
            if(next>=0 && next<$inputs.length) $inputs.eq(next).focus().select();
            return;
        }
        if (e.key>='1' && e.key<='4'){
            e.preventDefault(); $(this).val(e.key).trigger('change');
            if (idx < $inputs.length-1) setTimeout(()=> $inputs.eq(idx+1).focus().select(), 80);
        }
    });

    // fokus awal
    const $firstEmpty = $('.nilai-input').filter(function(){ return $(this).val()===''; }).first();
    ($firstEmpty.length ? $firstEmpty : $('.nilai-input').first()).focus();

    $('#formPenilaian').on('submit', function(e){ e.preventDefault(); submitAjax(false); });
    $('#btnSimpanLanjut').on('click', function(){ submitAjax(true); });

    function submitAjax(goNext){
        const $form = $('#formPenilaian');
        const url   = $form.attr('action');
        const data  = $form.serialize();
        $('#btnSimpan, #btnSimpanLanjut').prop('disabled', true);
        $('#btnSimpan').html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');

        $.post(url, data).done(function(){
            showNotif('ok','Data berhasil disimpan!');
            if(goNext){
                const href = @json($nextId ? route('penilaian.input', ['id'=>$nextId, 'skin'=>$skin]) : null);
                if (href) window.location.href = href;
            }
        }).fail(function(xhr){
            const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Gagal menyimpan data.';
            showNotif('err', msg);
        }).always(function(){
            $('#btnSimpan, #btnSimpanLanjut').prop('disabled', false);
            $('#btnSimpan').html('<i class="bi bi-save"></i> Simpan');
        });
    }
});
</script>
@endsection
