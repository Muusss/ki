@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Edit Penilaian: {{ $alternatif->nama_menu }}</h3>
    <a href="{{ route('penilaian') }}" class="btn btn-light">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        @include('dashboard.penilaian._form', ['alternatif'=>$alternatif,'kriteria'=>$kriteria,'rows'=>$rows])
    </div>
</div>
@endsection
