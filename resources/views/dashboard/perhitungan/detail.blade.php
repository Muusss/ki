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
                        <strong>Contoh untuk Menu Cafe:</strong>
                        <ul class="mt-2">
                            <li>Rasa (semakin enak semakin baik)</li>
                            <li>Porsi (semakin besar semakin baik)</li>
                            <li>Kualitas bahan (semakin fresh semakin baik)</li>
                            <li>Presentasi/tampilan (semakin menarik semakin baik)</li>
                            <li>Variasi menu (semakin bervariasi semakin baik)</li>
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
                        <strong>Contoh untuk Menu Cafe:</strong>
                        <ul class="mt-2">
                            <li>Harga (semakin murah semakin baik)</li>
                            <li>Waktu penyajian (semakin cepat semakin baik)</li>
                            <li>Tingkat kalori (untuk menu sehat, semakin rendah semakin baik)</li>
                            <li>Kandungan gula (untuk minuman sehat, semakin rendah semakin baik)</li>
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
                    <h5 class="mb-0">Klasifikasi Kriteria Menu Cafe</h5>
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
                                    ['C1', 'Rasa', 'benefit', 'Semakin enak semakin baik', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C2', 'Harga', 'cost', 'Harga murah = lebih ekonomis', '(Xmax - Xi) / (Xmax - Xmin)'],
                                    ['C3', 'Porsi', 'benefit', 'Porsi besar = lebih mengenyangkan', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C4', 'Waktu Penyajian', 'cost', 'Semakin cepat = lebih efisien', '(Xmax - Xi) / (Xmax - Xmin)'],
                                    ['C5', 'Kualitas Bahan', 'benefit', 'Bahan fresh = lebih sehat', '(Xi - Xmin) / (Xmax - Xmin)'],
                                    ['C6', 'Presentasi', 'benefit', 'Tampilan menarik = lebih menggugah', '(Xi - Xmin) / (Xmax - Xmin)'],
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
                            <h6 class="text-success">Contoh Kriteria BENEFIT (Rasa)</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Data Penilaian Rasa (skala 1-4):</strong></p>
                                    <ul>
                                        <li>Nasi Goreng Spesial: 4 (Sangat Enak)</li>
                                        <li>Mie Ayam Bakso: 3 (Enak)</li>
                                        <li>Soto Ayam: 2 (Cukup)</li>
                                    </ul>
                                    
                                    <p><strong>Min = 2, Max = 4</strong></p>
                                    
                                    <p><strong>Perhitungan Utility:</strong></p>
                                    <ul>
                                        <li>Nasi Goreng: (4-2)/(4-2) = 2/2 = <strong>1.000</strong> ✅ Terbaik</li>
                                        <li>Mie Ayam: (3-2)/(4-2) = 1/2 = <strong>0.500</strong></li>
                                        <li>Soto Ayam: (2-2)/(4-2) = 0/2 = <strong>0.000</strong></li>
                                    </ul>
                                    
                                    <div class="alert alert-success mt-2">
                                        <small><strong>Kesimpulan:</strong> Nasi Goreng mendapat utility tertinggi (1.000) karena rasa paling enak</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contoh Cost -->
                        <div class="col-md-6">
                            <h6 class="text-warning">Contoh Kriteria COST (Harga)</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Data Harga Menu:</strong></p>
                                    <ul>
                                        <li>Nasi Goreng Spesial: Rp 25.000</li>
                                        <li>Mie Ayam Bakso: Rp 20.000</li>
                                        <li>Soto Ayam: Rp 18.000</li>
                                    </ul>
                                    
                                    <p><strong>Min = 18.000, Max = 25.000</strong></p>
                                    
                                    <p><strong>Perhitungan Utility:</strong></p>
                                    <ul>
                                        <li>Nasi Goreng: (25000-25000)/(25000-18000) = 0/7000 = <strong>0.000</strong></li>
                                        <li>Mie Ayam: (25000-20000)/(25000-18000) = 5000/7000 = <strong>0.714</strong></li>
                                        <li>Soto Ayam: (25000-18000)/(25000-18000) = 7000/7000 = <strong>1.000</strong> ✅ Termurah</li>
                                    </ul>
                                    
                                    <div class="alert alert-warning mt-2">
                                        <small><strong>Kesimpulan:</strong> Soto Ayam mendapat utility tertinggi (1.000) karena harga paling ekonomis</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contoh Waktu Penyajian -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-warning">Contoh Kriteria COST (Waktu Penyajian)</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>Data Waktu Penyajian (menit):</strong></p>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <ul>
                                                <li>Es Teh Manis: 2 menit</li>
                                                <li>Cappuccino: 5 menit</li>
                                                <li>Milkshake: 8 menit</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-8">
                                            <p><strong>Min = 2, Max = 8</strong></p>
                                            <p><strong>Perhitungan:</strong></p>
                                            <ul>
                                                <li>Es Teh: (8-2)/(8-2) = 6/6 = <strong>1.000</strong> ✅ Tercepat</li>
                                                <li>Cappuccino: (8-5)/(8-2) = 3/6 = <strong>0.500</strong></li>
                                                <li>Milkshake: (8-8)/(8-2) = 0/6 = <strong>0.000</strong></li>
                                            </ul>
                                        </div>
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
                            <h6 class="text-success">✓ Kriteria Benefit (Menu Cafe)</h6>
                            <ul>
                                <li>Nilai tinggi → Utility tinggi → Lebih disukai</li>
                                <li>Range: 0.000 (terburuk) sampai 1.000 (terbaik)</li>
                                <li>Contoh: Rasa, Porsi, Kualitas, Presentasi</li>
                                <li>Prinsip: "More is Better"</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-warning">✓ Kriteria Cost (Menu Cafe)</h6>
                            <ul>
                                <li>Nilai rendah → Utility tinggi → Lebih disukai</li>
                                <li>Range: 0.000 (terburuk) sampai 1.000 (terbaik)</li>
                                <li>Contoh: Harga, Waktu Penyajian, Kalori</li>
                                <li>Prinsip: "Less is Better"</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong><i class="bi bi-info-circle"></i> Catatan untuk Cafe Buri Umah:</strong> 
                        <ul class="mb-0 mt-2">
                            <li>Setelah normalisasi, semua kriteria memiliki skala yang sama (0-1)</li>
                            <li>Nilai utility dikombinasikan dengan bobot ROC untuk mendapat nilai akhir</li>
                            <li>Menu dengan total nilai tertinggi adalah rekomendasi terbaik</li>
                            <li>Sistem dapat memfilter berdasarkan jenis menu (makanan, minuman, cemilan) dan kategori harga</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection