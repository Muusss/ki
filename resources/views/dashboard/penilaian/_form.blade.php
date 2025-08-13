<form method="POST" action="{{ route('penilaian.update', ['id' => $alternatif->id]) }}">
    @csrf

    <div class="mb-2">
        <strong>{{ $alternatif->kode_produk }} - {{ $alternatif->nama_produk }}</strong>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Kriteria</th>
                <th style="width:180px">Nilai Asli</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kriteria as $k)
            @php
                $row = collect(data_get($rows, $k->id, []))->first();
            @endphp
            <tr>
                <td>{{ $k->kode }}</td>
                <td>{{ $k->kriteria }}</td>
                <td>
                    <input
                        type="number" step="1" min="0"
                        class="form-control @error('nilai_asli.'.$k->id) is-invalid @enderror"
                        name="nilai_asli[{{ $k->id }}]"
                        value="{{ old('nilai_asli.'.$k->id, $row->nilai_asli ?? '') }}"
                    >
                    @error('nilai_asli.'.$k->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>