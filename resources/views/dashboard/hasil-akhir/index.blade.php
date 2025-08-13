@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Hasil Akhir Perankingan Produk Terbaik</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Hasil Akhir</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('perhitungan') }}" class="btn btn-secondary">
                        <i class="bi bi-calculator"></i> Lihat Perhitungan
                    </a>
                    <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
        <!-- Ringkasan Top 3 -->
        <div class="row mb-4">
            @php
                $top3 = $nilaiAkhir->take(3);
            @endphp
            @foreach($top3 as $index => $produk)
            <div class="col-md-4">
                <div class="card {{ $index == 0 ? 'border-warning' : ($index == 1 ? 'border-secondary' : 'border-danger') }} border-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($index == 0)
                                <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-2 text-warning">JUARA 1</h5>
                            @elseif($index == 1)
                                <i class="bi bi-award-fill text-secondary" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-2 text-secondary">JUARA 2</h5>
                            @else
                                <i class="bi bi-award-fill text-danger" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-2 text-danger">JUARA 3</h5>
                            @endif
                        </div>
                        <h4 class="card-title">{{ $produk->alternatif->nama_produk ?? '-' }}</h4>
                        <p class="text-muted mb-1">Kode: {{ $produk->alternatif->kode_produk ?? '-' }}</p>
                        <p class="text-muted mb-3">Jenis Kulit: {{ $produk->alternatif->jenis_kulit ?? '-' }}</p>
                        <h3 class="text-primary">{{ number_format($produk->total ?? 0, 4) }}</h3>
                        <span class="badge bg-success">Produk Terbaik</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabel Lengkap Perankingan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tabel Perankingan Produk Sunscreen</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblHasil" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">Peringkat</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Jenis Kulit</th>
                                        <th class="text-center">Total Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilaiAkhir as $row)
                                    <tr class="{{ $loop->iteration <= 3 ? 'table-success' : '' }}">
                                        <td class="text-center">
                                            @if($loop->iteration == 1)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> {{ $row->peringkat ?? $loop->iteration }}
                                                </span>
                                            @elseif($loop->iteration == 2)
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="bi bi-award-fill"></i> {{ $row->peringkat ?? $loop->iteration }}
                                                </span>
                                            @elseif($loop->iteration == 3)
                                                <span class="badge bg-danger fs-6">
                                                    <i class="bi bi-award-fill"></i> {{ $row->peringkat ?? $loop->iteration }}
                                                </span>
                                            @else
                                                <span class="badge bg-info">{{ $row->peringkat ?? $loop->iteration }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->alternatif->kode_produk ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $row->alternatif->nama_produk ?? '-' }}</strong>
                                            @if($loop->iteration == 1)
                                                <i class="bi bi-star-fill text-warning ms-2"></i>
                                            @endif
                                        </td>
                                        <td>{{ $row->alternatif->jenis_kulit ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">
                                                {{ number_format($row->total ?? 0, 4) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($loop->iteration == 1)
                                                <span class="badge bg-success">Produk Terbaik</span>
                                            @elseif($loop->iteration <= 3)
                                                <span class="badge bg-info">Nominasi</span>
                                            @elseif($loop->iteration <= 10)
                                                <span class="badge bg-secondary">10 Besar</span>
                                            @else
                                                <span class="badge bg-light text-dark">Partisipan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $nilaiAkhir->count() }}</h3>
                        <p class="text-muted mb-0">Total Produk</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ number_format($nilaiAkhir->max('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Tertinggi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ number_format($nilaiAkhir->avg('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Rata-rata</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($nilaiAkhir->min('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Terendah</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan jika belum ada data -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Belum Ada Data Hasil Perhitungan</h4>
                        <p class="text-muted">Silakan lakukan perhitungan ROC + SMART terlebih dahulu.</p>
                        <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                            <i class="bi bi-calculator"></i> Ke Halaman Perhitungan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
    $('#tblHasil').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });
    @endif
});
</script>
@endsection