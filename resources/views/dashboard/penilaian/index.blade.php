@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Penilaian Alternatif</h3>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblPenilaian" class="table table-striped">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        @forelse(($kriteria ?? []) as $k)
                            <th class="text-center">{{ $k->kriteria }}</th>
                        @empty
                            <th class="text-center">Kriteria</th>
                        @endforelse
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse(($alternatif ?? []) as $alt)
                    <tr>
                        <td>{{ $alt->kode_produk }} - {{ $alt->nama_produk }}</td>

                        @forelse(($kriteria ?? []) as $k)
                            @php
                                // Ambil baris penilaian untuk alternatif & kriteria
                                $row = null;
                                if (isset($penilaian) && isset($penilaian[$alt->id][$k->id])) {
                                    $row = collect($penilaian[$alt->id][$k->id])->first();
                                }
                            @endphp
                            <td class="text-center">
                                @if($row && $row->nilai_asli !== null)
                                    {{ number_format((float) $row->nilai_asli, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        @empty
                            <td class="text-center">-</td>
                        @endforelse

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-warning"
                                onclick="editPenilaian({{ $alt->id }}, @js($alt->nama_produk))">
                                Edit
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + (isset($kriteria) ? $kriteria->count() : 0) }}" class="text-center">
                            Belum ada data alternatif
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Penilaian (konten form akan dimuat via AJAX) -->
<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Penilaian: <span id="nama_produk">-</span>
                </h5>
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
function editPenilaian(alternatifId, namaProduk) {
    const $modal = $('#modalPenilaian');
    $modal.find('#nama_produk').text(namaProduk);
    $modal.find('.modal-body').html('<div class="text-center py-4">Memuat formulir...</div>');
    $modal.modal('show');

    // Buat URL dari named route dengan placeholder, lalu replace
    let url = @json(route('penilaian.edit', ['id' => '__ID__']));
    url = url.replace('__ID__', encodeURIComponent(alternatifId));

    $.get(url, function (html) {
        $modal.find('.modal-body').html(html);
    }).fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Silakan coba lagi.';
        $modal.find('.modal-body').html('<div class="alert alert-danger">Gagal memuat formulir. ' + msg + '</div>');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.jQuery && $.fn.DataTable) {
        $('#tblPenilaian').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { first: "Pertama", last: "Terakhir", next: "Selanjutnya", previous: "Sebelumnya" }
            }
        });
    }
});
</script>
@endsection