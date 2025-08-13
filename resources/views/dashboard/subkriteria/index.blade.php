@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $title ?? 'Data Sub Kriteria' }}</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
    Tambah Sub Kriteria
  </button>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblSub" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>Kriteria</th>
            <th>Label</th>
            <th>Skor</th>
            <th>Min</th>
            <th>Max</th>
            <th style="width:130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse($sub as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ ($row->kriteria->kode ?? '-') }} - {{ ($row->kriteria->kriteria ?? '-') }}</td>
            <td>{{ $row->label }}</td>
            <td>{{ $row->skor }}</td>
            <td>{{ $row->min_val ?? '-' }}</td>
            <td>{{ $row->max_val ?? '-' }}</td>
            <td class="text-nowrap">
              <button class="btn btn-sm btn-warning"
                      data-bs-toggle="modal" data-bs-target="#modalForm"
                      onclick="show_button({{ $row->id }})">Edit</button>
              <form action="{{ route('subkriteria.delete') }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Hapus sub kriteria ini?')">
                @csrf
                <input type="hidden" name="id" value="{{ $row->id }}">
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal Create / Edit --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formSub" method="POST" action="{{ route('subkriteria.store') }}">
        @csrf
        <input type="hidden" name="id"> {{-- diisi saat edit --}}
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Sub Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Kriteria</label>
              <select name="kriteria_id" class="form-select" required>
                <option value="" disabled selected>Pilih</option>
                @foreach($kriteria as $k)
                  <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->kriteria }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Label</label>
              <input type="text" class="form-control" name="label" required maxlength="100"
                     placeholder="contoh: ≥ 91 / Sangat Baik / 1 Juz">
            </div>
            <div class="col-md-4">
              <label class="form-label">Skor (1–4)</label>
              <select name="skor" class="form-select" required>
                <option value="" disabled selected>Pilih</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Min</label>
              <input type="number" class="form-control" name="min_val" step="1" placeholder="opsional">
            </div>
            <div class="col-md-4">
              <label class="form-label">Max</label>
              <input type="number" class="form-control" name="max_val" step="1" placeholder="opsional">
            </div>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger mt-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(function () {
  $('#tblSub').DataTable({
    responsive: true,
    pagingType: 'full_numbers',
    order: [[1, 'asc'], [3, 'asc']] // Kriteria, lalu Skor 1→4
  });
});

function create_button() {
  $('#modalTitle').text('Tambah Sub Kriteria');
  $('#formSub').attr('action', '{{ route('subkriteria.store') }}');
  $('#formSub input[name=_method]').remove();

  $('#formSub input[name=id]').val('');
  $('#formSub select[name=kriteria_id]').val('');
  $('#formSub input[name=label]').val('');
  $('#formSub select[name=skor]').val('');
  $('#formSub input[name=min_val]').val('');
  $('#formSub input[name=max_val]').val('');
}

function show_button(sub_id) {
  $('#modalTitle').text('Edit Sub Kriteria');
  $('#formSub').attr('action', '{{ route('subkriteria.update') }}');
  if (!$('#formSub input[name=_method]').length) {
    $('#formSub').append('<input type="hidden" name="_method" value="POST">'); // rute update menerima POST
  }
  $('#btnSubmit').prop('disabled', true).text('Memuat...');

  $.ajax({
    type: 'GET',
    url: '{{ route('subkriteria.edit') }}',
    data: { _token: '{{ csrf_token() }}', sub_kriteria_id: sub_id },
    success: function (d) {
      $('#formSub input[name=id]').val(d.id);
      $('#formSub select[name=kriteria_id]').val(d.kriteria_id);
      $('#formSub input[name=label]').val(d.label);
      $('#formSub select[name=skor]').val(d.skor);
      $('#formSub input[name=min_val]').val(d.min_val ?? '');
      $('#formSub input[name=max_val]').val(d.max_val ?? '');
    },
    error: function () { alert('Gagal memuat data.'); },
    complete: function () { $('#btnSubmit').prop('disabled', false).text('Simpan'); }
  });
}
</script>
@endsection
