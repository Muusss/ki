@extends('dashboard.layouts.main')

@section('content')
@php
    $alternatif = collect($alternatif ?? []);
    $kriteria   = collect($kriteria ?? []);
    $penilaian  = $penilaian ?? [];
    $jenisQuery = request('jenis'); // Filter by jenis_menu
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2" id="penilaianHeader"
     data-init-jenis="{{ $jenisQuery }}">
    <h3 class="mb-0">Penilaian Menu</h3>

    <div class="btn-group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-funnel"></i> Filter Jenis Menu
            <span id="lblFilterJenis" class="text-muted ms-1">(Semua)</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="all">Semua Menu</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="makanan">Makanan</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="cemilan">Cemilan</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="coffee">Coffee</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="milkshake">Milkshake</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="mojito">Mojito</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="yakult">Yakult</a></li>
            <li><a class="dropdown-item filter-jenis" href="#" data-filter="tea">Tea</a></li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblPenilaian" class="table table-striped">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jenis Menu</th>
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
                        <td>{{ ($alt->kode_menu ?? '-') . ' - ' . ($alt->nama_menu ?? '-') }}</td>
                        <td class="text-capitalize" data-jenis="{{ $alt->jenis_menu ?? '' }}">
                            @php
                                $jenisLabel = \App\Models\Alternatif::JENIS_MENU[$alt->jenis_menu] ?? ucfirst($alt->jenis_menu ?? '-');
                            @endphp
                            <span class="badge bg-{{ 
                                match($alt->jenis_menu) {
                                    'makanan' => 'success',
                                    'cemilan' => 'warning',
                                    'coffee' => 'dark',
                                    'milkshake' => 'info',
                                    'mojito' => 'danger',
                                    'yakult' => 'primary',
                                    'tea' => 'secondary',
                                    default => 'secondary'
                                } 
                            }}">
                                {{ $jenisLabel }}
                            </span>
                        </td>

                        @forelse($kriteria as $k)
                            @php $row = $penilaian[$alt->id][$k->id][0] ?? null; @endphp
                            <td class="text-center">
                                @if($row && $row->nilai_asli !== null)
                                    <span class="badge bg-primary">{{ number_format((float) $row->nilai_asli, 0) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
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
                                data-name="{{ $alt->nama_menu }}"
                                data-url="{{ route('penilaian.edit', ['id' => $alt->id]) }}"
                                onclick="editPenilaian(this)">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $kriteria->count() }}" class="text-center">
                            Belum ada data menu
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
                <h5 class="modal-title">Edit Penilaian: <span id="nama_menu">-</span></h5>
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
let currentJenis = 'all';

const LABELS = {
    all: 'Semua',
    makanan: 'Makanan',
    cemilan: 'Cemilan',
    coffee: 'Coffee',
    milkshake: 'Milkshake',
    mojito: 'Mojito',
    yakult: 'Yakult',
    tea: 'Tea'
};

document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi currentJenis dari query server (jika ada)
    const initJenis = document.getElementById('penilaianHeader').getAttribute('data-init-jenis');
    if (initJenis && Object.keys(LABELS).includes(initJenis)) {
        currentJenis = initJenis;
        document.getElementById('lblFilterJenis').textContent = '(' + (LABELS[currentJenis] || 'Semua') + ')';
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
        if (currentJenis !== 'all') {
            penilaianTable.column(1).search(currentJenis, false, false).draw();
        }
    }

    $(document).on('click', '.filter-jenis', function(e) {
        e.preventDefault();
        const val = $(this).data('filter');
        currentJenis = val;
        $('#lblFilterJenis').text('(' + (LABELS[val] || 'Semua') + ')');
        if (!penilaianTable) return;

        if (val === 'all') {
            penilaianTable.column(1).search('').draw();
        } else {
            penilaianTable.column(1).search(val, false, false).draw();
        }
    });
});

// === buka halaman input penuh, teruskan ?jenis= saat ini ===
function goInput(btn){
    const base = btn.getAttribute('data-url-input');
    if (!base) return;
    const qs = (currentJenis && currentJenis !== 'all') ? ('?jenis=' + encodeURIComponent(currentJenis)) : '';
    window.location.href = base + qs;
}

// === edit cepat via modal (tetap tersedia) ===
function editPenilaian(btn) {
    const $btn = $(btn);
    const nama = $btn.data('name') || '-';
    const url  = $btn.data('url');

    const $modal = $('#modalPenilaian');
    $modal.find('#nama_menu').text(nama);
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