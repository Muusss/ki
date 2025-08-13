@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Detail Perhitungan Benefit vs Cost</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('perhitungan') }}">Perhitungan</a></li>
                            <li class="breadcrumb-item active">Detail Benefit vs Cost</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjelasan Benefit vs Cost -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-arrow-up-circle"></i> Kriteria BENEFIT</h5>
                </div>
                <div class="card-body">
                    <p><strong>Karakteristik:</strong> Semakin tinggi nilai semakin baik</p>
                    <p><strong>Formula Normalisasi:</strong></p>
                    <code>U<sub>i</sub> = (X<sub>i</sub> - X<sub>min</sub>) / (X<sub>max</sub> - X<sub>min</sub>)</code>
                    
                    <div class="mt-3">
                        <strong>Contoh untuk Sunscreen:</strong>
                        <ul class="mt-2">
                            <li>SPF (semakin tinggi semakin baik)</li>
                            <li>Kesesuaian jenis kulit</li>
                            <li>Tekstur</li>
                            <li>Ukuran kemasan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-arrow-down-circle"></i> Kriteria COST</h5>
                </div>
                <div class="card-body">
                    <p><strong>Karakteristik:</strong> Semakin rendah nilai semakin baik</p>
                    <p><strong>Formula Normalisasi:</strong></p>
                    <code>U<sub>i</sub> = (X<sub>max</sub> - X<sub>i</sub>) / (X<sub>max</sub> - X<sub>min</sub>)</code>
                    
                    <div class="mt-3">
                        <strong>Contoh untuk Sunscreen:</strong>
                        <ul class="mt-2">
                            <li>Harga (semakin murah semakin baik)</li>
                            <li>Komposisi berbahaya</li>
                            <li>Efek samping</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kriteria dengan Atribut -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Klasifikasi Kriteria Sunscreen</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Kriteria</th>
                                    <th>Atribut</th>
                                    <th>Penjelasan</th>
                                    <th>Formula yang Digunakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $kriteriaData = [
                                    ['C1', 'Kesesuaian Jenis Kulit', 'benefit', 'Semakin sesuai semakin baik', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C2', 'SPF', 'benefit', 'SPF tinggi = perlindungan lebih baik', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C3', 'Harga', 'cost', 'Harga murah = lebih ekonomis', '(Xmax - Xi) / (Xmax - Xmin)'],
                                    ['C4', 'Komposisi', 'cost', 'Sedikit bahan berbahaya = lebih aman', '(Xmax - Xi) / (Xmax - Xmin)'],
                                    ['C5', 'Efek Samping', 'cost', 'Minim efek samping = lebih baik', '(Xmax - Xi) / (Xmax - Xmin)'],
                                    ['C6', 'Tekstur', 'benefit', 'Tekstur nyaman = lebih baik', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C7', 'Ukuran', 'benefit', 'Ukuran besar = lebih tahan lama', '(Xi - Xmin) / (Xmax - Xmin)'],
                                ];
                                @endphp
                                
                                @foreach($kriteriaData as $k)
                                <tr>
                                    <td><strong>{{ $k[0] }}</strong></td>
                                    <td>{{ $k[1] }}</td>
                                    <td>
                                        <span class="badge bg-{{ $k[2] === 'benefit' ? 'success' : 'warning' }}">
                                            {{ ucfirst($k[2]) }}
                                        </span>
                                    </td>
                                    <td>{{ $k[3] }}</td>
                                    <td><code>{{ $k[4] }}</code></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contoh Perhitungan -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Contoh Perhitungan Normalisasi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Contoh Benefit -->
                        <div class="col-md-6">
                            <h6 class="text-success">Contoh Kriteria BENEFIT (SPF)</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Data:</strong></p>
                                    <ul>
                                        <li>Produk A: SPF 30</li>
                                        <li>Produk B: SPF 40</li>
                                        <li>Produk C: SPF 50</li>
                                    </ul>
                                    
                                    <p><strong>Min = 30, Max = 50</strong></p>
                                    
                                    <p><strong>Perhitungan Utility:</strong></p>
                                    <ul>
                                        <li>Produk A: (30-30)/(50-30) = 0/20 = <strong>0.000</strong></li>
                                        <li>Produk B: (40-30)/(50-30) = 10/20 = <strong>0.500</strong></li>
                                        <li>Produk C: (50-30)/(50-30) = 20/20 = <strong>1.000</strong></li>
                                    </ul>
                                    
                                    <div class="alert alert-success mt-2">
                                        <small><strong>Kesimpulan:</strong> SPF 50 mendapat utility tertinggi (1.000) karena perlindungan terbaik</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contoh Cost -->
                        <div class="col-md-6">
                            <h6 class="text-warning">Contoh Kriteria COST (Harga)</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Data:</strong></p>
                                    <ul>
                                        <li>Produk A: Rp 30.000</li>
                                        <li>Produk B: Rp 50.000</li>
                                        <li>Produk C: Rp 80.000</li>
                                    </ul>
                                    
                                    <p><strong>Min = 30.000, Max = 80.000</strong></p>
                                    
                                    <p><strong>Perhitungan Utility:</strong></p>
                                    <ul>
                                        <li>Produk A: (80000-30000)/(80000-30000) = 50000/50000 = <strong>1.000</strong></li>
                                        <li>Produk B: (80000-50000)/(80000-30000) = 30000/50000 = <strong>0.600</strong></li>
                                        <li>Produk C: (80000-80000)/(80000-30000) = 0/50000 = <strong>0.000</strong></li>
                                    </ul>
                                    
                                    <div class="alert alert-warning mt-2">
                                        <small><strong>Kesimpulan:</strong> Harga Rp 30.000 mendapat utility tertinggi (1.000) karena paling ekonomis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kesimpulan -->
    <div class="row">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Kesimpulan Penting</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">✓ Benefit Criteria</h6>
                            <ul>
                                <li>Nilai asli tinggi → Utility tinggi → Lebih disukai</li>
                                <li>Range: 0.000 (terburuk) sampai 1.000 (terbaik)</li>
                                <li>Contoh: SPF, Kualitas, Ukuran</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">✓ Cost Criteria</h6>
                            <ul>
                                <li>Nilai asli rendah → Utility tinggi → Lebih disukai</li>
                                <li>Range: 0.000 (terburuk) sampai 1.000 (terbaik)</li>
                                <li>Contoh: Harga, Efek Samping, Komposisi Berbahaya</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong><i class="bi bi-info-circle"></i> Catatan:</strong> 
                        Setelah normalisasi, semua kriteria (benefit dan cost) memiliki skala yang sama (0-1), 
                        sehingga dapat dibandingkan dan dikombinasikan dalam perhitungan nilai akhir.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection