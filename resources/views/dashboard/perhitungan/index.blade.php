@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Perhitungan Metode ROC + SMART</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Perhitungan</li>
                        </ol>
                    </nav>
                </div>
                
                {{-- Tombol Proses ROC + SMART --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-success" onclick="prosesROCSMART()">
                        <i class="bi bi-calculator"></i> Proses ROC + SMART
                    </button>
                    <a href="{{ route('pdf.hasilAkhir') }}" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Step Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="steps-progress">
                        <div class="step-item completed">
                            <div class="step-number">1</div>
                            <div class="step-title">Data Produk</div>
                        </div>
                        <div class="step-item completed">
                            <div class="step-number">2</div>
                            <div class="step-title">Kriteria & Bobot ROC</div>
                        </div>
                        <div class="step-item completed">
                            <div class="step-number">3</div>
                            <div class="step-title">Penilaian</div>
                        </div>
                        <div class="step-item active">
                            <div class="step-number">4</div>
                            <div class="step-title">Perhitungan SMART</div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">5</div>
                            <div class="step-title">Hasil Akhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <h5 class="alert-heading"><i class="bi bi-info-circle"></i> Informasi Perhitungan</h5>
                <p class="mb-0">Klik tombol <strong>"Proses ROC + SMART"</strong> untuk menghitung ulang:</p>
                <ul class="mb-0 mt-2">
                    <li>Bobot kriteria dengan metode ROC (Rank Order Centroid)</li>
                    <li>Normalisasi nilai dengan metode SMART</li>
                    <li>Nilai akhir dan perankingan produk</li>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>

    <!-- Bobot ROC -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">1. Bobot Kriteria (Metode ROC)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kriteria</th>
                                <th>Atribut</th>
                                <th>Prioritas</th>
                                <th>Bobot ROC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($kriteria ?? []) as $k)
                            <tr>
                                <td>{{ $k->kode }}</td>
                                <td>{{ $k->kriteria }}</td>
                                <td>
                                    <span class="badge bg-{{ $k->atribut === 'benefit' ? 'success' : 'warning' }}">
                                        {{ ucfirst($k->atribut) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $k->urutan_prioritas }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($k->bobot_roc ?? 0, 4) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data kriteria</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3">Total Bobot</th>
                                <th>{{ number_format(($sumBobotKriteria ?? 0), 4) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formula ROC -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Formula ROC & SMART</h5>
                </div>
                <div class="card-body">
                    <div class="formula-box">
                        <h6>Rumus Rank Order Centroid:</h6>
                        <div class="text-center my-3">
                            <code>W_m = (1/m) × Σ(1/i)</code>
                        </div>
                        
                        <h6 class="mt-4">Rumus SMART Normalisasi:</h6>
                        <div class="my-3">
                            <strong>Benefit (semakin tinggi semakin baik):</strong><br>
                            <code>U_i = (X_i - X_min) / (X_max - X_min)</code><br><br>
                            
                            <strong>Cost (semakin rendah semakin baik):</strong><br>
                            <code>U_i = (X_max - X_i) / (X_max - X_min)</code>
                        </div>
                        
                        @if(isset($infoNormalisasi) && count($infoNormalisasi) > 0)
                        <div class="alert alert-success mt-3">
                            <strong>Info Perhitungan per Kriteria:</strong>
                            @foreach($infoNormalisasi as $info)
                            <div class="mt-2">
                                <strong>{{ $info['kriteria'] }}</strong> ({{ ucfirst($info['atribut']) }})<br>
                                <small>Range: {{ $info['min'] }} - {{ $info['max'] }}</small><br>
                                <small>Formula: {{ $info['formula'] }}</small>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Normalisasi Matriks -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">2. Normalisasi Matriks (Metode SMART)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Produk</th>
                                    <th colspan="{{ isset($kriteria) ? $kriteria->count() : 0 }}" class="text-center">
                                        Nilai Normalisasi (0-1)
                                    </th>
                                </tr>
                                <tr>
                                    @forelse(($kriteria ?? []) as $k)
                                        <th class="text-center">{{ $k->kode }}</th>
                                    @empty
                                        <th class="text-center">Belum ada kriteria</th>
                                    @endforelse
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($alternatif ?? collect())->take(10) as $alt)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $alt->nama_produk }}</td>
                                    @forelse(($kriteria ?? []) as $k)
                                        @php
                                            $nilai = isset($penilaian) ? $penilaian->where('alternatif_id', $alt->id)
                                                                      ->where('kriteria_id', $k->id)
                                                                      ->first() : null;
                                        @endphp
                                        <td class="text-center">
                                            @if($nilai && $nilai->nilai_normal !== null)
                                                <span class="badge bg-primary">
                                                    {{ number_format($nilai->nilai_normal, 3) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @empty
                                        <td class="text-center">-</td>
                                    @endforelse
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 2 + (isset($kriteria) ? $kriteria->count() : 1) }}" class="text-center">
                                        Belum ada data produk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Formula Normalisasi SMART:</strong><br>
                        Benefit: U<sub>i</sub> = (X<sub>i</sub> - X<sub>min</sub>) / (X<sub>max</sub> - X<sub>min</sub>)<br>
                        Cost: U<sub>i</sub> = (X<sub>max</sub> - X<sub>i</sub>) / (X<sub>max</sub> - X<sub>min</sub>)<br><br>
                        <a href="{{ route('smart.detail.benefit.cost') }}" class="btn btn-sm btn-info">
                            <i class="bi bi-info-circle"></i> Lihat Detail Benefit vs Cost
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Akhir -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">3. Nilai Akhir (ROC × Utility)</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Produk</th>
                                @forelse(($kriteria ?? []) as $krit)
                                    <th class="text-center">{{ $krit->kode }}</th>
                                @empty
                                    <th class="text-center">Kriteria</th>
                                @endforelse
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($alternatif ?? []) as $alt)
                                @php
                                    $total = isset($hasil) ? $hasil->where('alternatif_id', $alt->id)->first()->total ?? 0 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <p class="text-left align-middle text-base font-semibold leading-tight">
                                            {{ $alt->nama_produk }}
                                        </p>
                                    </td>
                                    @forelse(($kriteria ?? []) as $krit)
                                        <td class="text-center">
                                            <p class="align-middle text-base font-semibold leading-tight">
                                                {{ number_format(0, 3) }}
                                            </p>
                                        </td>
                                    @empty
                                        <td class="text-center">-</td>
                                    @endforelse
                                    <td class="text-center">
                                        <p class="align-middle text-base font-bold leading-tight">
                                            {{ number_format($total, 3) }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 2 + (isset($kriteria) ? $kriteria->count() : 0) }}" class="text-center">
                                        Belum ada data produk
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Link ke Hasil Akhir -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center py-5">
                    <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Lihat Hasil Akhir Perankingan</h4>
                    <p class="text-muted">Hasil perhitungan telah selesai. Klik tombol di bawah untuk melihat perankingan produk terbaik.</p>
                    <a href="{{ route('hasil-akhir') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-right-circle"></i> Lihat Hasil Akhir
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.steps-progress {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.step-item {
    text-align: center;
    flex: 1;
    position: relative;
}

.step-item::before {
    content: '';
    position: absolute;
    top: 20px;
    left: -50%;
    right: 50%;
    height: 2px;
    background: #dee2e6;
    z-index: -1;
}

.step-item:first-child::before {
    display: none;
}

.step-item.completed::before,
.step-item.active::before {
    background: #28a745;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
}

.step-item.completed .step-number {
    background: #28a745;
}

.step-item.active .step-number {
    background: #ffc107;
    color: #000;
}

.formula-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function prosesROCSMART() {
    Swal.fire({
        title: 'Proses ROC + SMART',
        html: `
            <div class="text-start">
                <p>Proses ini akan menghitung ulang:</p>
                <ul>
                    <li>Bobot kriteria dengan metode ROC</li>
                    <li>Normalisasi nilai dengan metode SMART</li>
                    <li>Nilai akhir dan perankingan</li>
                </ul>
                <p class="text-warning mt-3"><strong>Perhatian:</strong> Proses ini akan memperbarui semua nilai!</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Proses!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses...',
                html: 'Sedang menghitung ROC + SMART, harap tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit form POST ke route perhitungan.smart
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("perhitungan.smart") }}';
            
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = '{{ csrf_token() }}';
            form.appendChild(token);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

$(document).ready(function() {
    // Cek jika ada session success
    @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            icon: 'success',
            confirmButtonColor: '#28a745'
        });
    @endif
});
</script>
@endsection