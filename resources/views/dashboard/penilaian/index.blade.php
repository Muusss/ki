@extends('dashboard.layouts.main')

@section('content')
@php
    $alternatif = collect($alternatif ?? []);
    $kriteria   = collect($kriteria ?? []);
    $penilaian  = $penilaian ?? [];
    // jika index ini juga dipanggil dengan ?skin=... dari server-side filter
    $skinQuery  = request('skin'); // null|all|normal|berminyak|kering|kombinasi
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2" id="penilaianHeader"
     data-init-skin="{{ $skinQuery }}">
    <h3 class="mb-0">Penilaian Alternatif</h3>

    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-funnel"></i> Filter Jenis Kulit
            <span id="lblFilterSkin" class="text-muted ms-1">(Semua)</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item filter-skin" href="#" data-filter="all">Semua Jenis</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item filter-skin" href="#" data-filter="normal">Kulit Normal</a></li>
            <li><a class="dropdown-item filter-skin" href="#" data-filter="berminyak">Kulit Berminyak</a></li>
            <li><a class="dropdown-item filter-skin" href="#" data-filter="kering">Kulit Kering</a></li>
            <li><a class="dropdown-item filter-skin" href="#" data-filter="kombinasi">Kulit Kombinasi</a></li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblPenilaian" class="table table-striped">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <th>Jenis Kulit</th>
                        @forelse($kriteria as $k)
                            <th class="text-center">{{ $k->kriteria ?? '-' }}</th>
                        @empty
                            <th class="text-center">Kriteria</th>
                        @endforelse
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($alternatif as $alt)
                    <tr>
                        <td>{{ ($alt->kode_produk ?? '-') . ' - ' . ($alt->nama_produk ?? '-') }}</td>
                        <td class="text-capitalize" data-skin="{{ $alt->jenis_kulit ?? '' }}">
                            {{ $alt->jenis_kulit ? ucfirst($alt->jenis_kulit) : '-' }}
                        </td>

                        @forelse($kriteria as $k)
                            @php $row = $penilaian[$alt->id][$k->id][0] ?? null; @endphp
                            <td class="text-center">
                                {{ ($row && $row->nilai_asli !== null) ? number_format((float) $row->nilai_asli, 2) : '-' }}
                            </td>
                        @empty
                            <td class="text-center">-</td>
                        @endforelse

                        <td class="text-center">
                            {{-- Buka halaman input penuh --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-primary me-1"
                                data-url-input="{{ route('penilaian.input', ['id' => $alt->id]) }}"
                                onclick="goInput(this)">
                                <i class="bi bi-pencil-square"></i> Input
                            </button>

                            {{-- Edit cepat (modal) --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-warning"
                                data-id="{{ (int) $alt->id }}"
                                data-name="{{ $alt->nama_produk }}"
                                data-url="{{ route('penilaian.edit', ['id' => $alt->id]) }}"
                                onclick="editPenilaian(this)">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $kriteria->count() }}" class="text-center">
                            Belum ada data alternatif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal untuk load _form via AJAX --}}
<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Penilaian: <span id="nama_produk">-</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">Memuat formulir...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
if (window.jQuery) {
    $.ajaxSetup({ headers: { 'X-Requested-With': 'XMLHttpRequest' } });
}

let penilaianTable;
let currentSkin = 'all';

const LABELS = {
    all: 'Semua',
    normal: 'Kulit Normal',
    berminyak: 'Kulit Berminyak',
    kering: 'Kulit Kering',
    kombinasi: 'Kulit Kombinasi'
};

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi currentSkin dari query server (jika ada)
    const initSkin = document.getElementById('penilaianHeader').getAttribute('data-init-skin');
    if (initSkin && ['all','normal','berminyak','kering','kombinasi'].includes(initSkin)) {
        currentSkin = initSkin;
        document.getElementById('lblFilterSkin').textContent = '(' + (LABELS[currentSkin] || 'Semua') + ')';
    }

    if (window.jQuery && $.fn.DataTable) {
        penilaianTable = $('#tblPenilaian').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
            },
            columnDefs: [{ targets: 1, searchable: true }]
        });

        // Terapkan filter awal bila ada
        if (currentSkin !== 'all') {
            penilaianTable.column(1).search('^' + currentSkin + '$', true, false).draw();
        }
    }

    $(document).on('click', '.filter-skin', function(e) {
        e.preventDefault();
        const val = $(this).data('filter');
        currentSkin = val;
        $('#lblFilterSkin').text('(' + (LABELS[val] || 'Semua') + ')');
        if (!penilaianTable) return;

        if (val === 'all') penilaianTable.column(1).search('').draw();
        else penilaianTable.column(1).search('^' + val + '$', true, false).draw();
    });
});

// === buka halaman input penuh, teruskan ?skin= saat ini ===
function goInput(btn){
    const base = btn.getAttribute('data-url-input');
    if (!base) return;
    const qs = (currentSkin && currentSkin !== 'all') ? ('?skin=' + encodeURIComponent(currentSkin)) : '';
    window.location.href = base + qs;
}

// === edit cepat via modal (tetap tersedia) ===
function editPenilaian(btn) {
    const $btn = $(btn);
    const nama = $btn.data('name') || '-';
    const url  = $btn.data('url');

    const $modal = $('#modalPenilaian');
    $modal.find('#nama_produk').text(nama);
    $modal.find('.modal-body').html('<div class="text-center py-4">Memuat formulir...</div>');
    $modal.modal('show');

    if (!url) {
        $modal.find('.modal-body').html('<div class="alert alert-danger">URL edit tidak tersedia.</div>');
        return;
    }

    $.get(url).done(function (html) {
        if (typeof html === 'string' && /<\s*html[^>]*>/i.test(html)) {
            window.location.href = url; // fallback full page
            return;
        }
        $modal.find('.modal-body').html(html);
    }).fail(function (xhr) {
        let msg = 'Silakan coba lagi.';
        if (xhr.status === 404) msg = 'Form tidak ditemukan (404).';
        if (xhr.status === 419) msg = 'Sesi kadaluarsa (419). Muat ulang halaman.';
        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;

        $modal.find('.modal-body').html(
            '<div class="alert alert-danger mb-3">Gagal memuat formulir. ' + msg + '</div>' +
            '<a class="btn btn-primary" href="'+ url +'">Buka Halaman Edit</a>'
        );
    });
}
</script>
@endsection
