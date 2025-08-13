@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $title ?? 'Data Kriteria' }}</h3>

  <div class="d-flex gap-2">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
      Tambah Kriteria
    </button>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblKriteria" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Kriteria</th>
            <th>Atribut</th>
            <th>Prioritas</th>
            <th>Bobot ROC</th>
            <th style="width:130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($kriteria as $k)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $k->kode }}</td>
              <td>{{ $k->kriteria }}</td>
              <td class="text-capitalize">{{ $k->atribut }}</td>
              <td>{{ $k->urutan_prioritas }}</td>
              <td>
                @if(!is_null($k->bobot_roc))
                  {{ number_format($k->bobot_roc, 4) }}
                @else
                  <span class="text-muted">Belum dihitung</span>
                @endif
              </td>
              <td class="text-nowrap">
                <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#modalForm"
                        onclick="show_button({{ $k->id }})">
                  Edit
                </button>

                <form action="{{ route('kriteria.delete') }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Hapus kriteria {{ $k->kode }} ?')">
                  @csrf
                  <input type="hidden" name="id" value="{{ $k->id }}">
                  <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>

        @if(isset($sumBobotKriteria))
        <tfoot>
          <tr>
            <th colspan="5" class="text-end">Total Bobot ROC</th>
            <th>{{ number_format($sumBobotKriteria, 4) }}</th>
            <th></th>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>
</div>

{{-- Modal Create / Edit --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formKriteria" method="POST" action="{{ route('kriteria.store') }}">
        @csrf
        <input type="hidden" name="id">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Kriteria</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Kode</label>
              <input type="text" class="form-control" name="kode" placeholder="C1" required maxlength="10">
            </div>
            <div class="col-md-9">
              <label class="form-label">Nama Kriteria</label>
              <input type="text" class="form-control" name="kriteria" required maxlength="100">
            </div>
            <div class="col-md-4">
              <label class="form-label">Atribut</label>
              <select class="form-select" name="atribut" required>
                <option value="" disabled selected>Pilih</option>
                <option value="benefit">Benefit</option>
                <option value="cost">Cost</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Urutan Prioritas</label>
              <input type="number" class="form-control" name="urutan_prioritas" min="1" step="1" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Bobot ROC (otomatis)</label>
              <input type="text" class="form-control" name="bobot_roc" readonly placeholder="Akan dihitung">
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
  $('#tblKriteria').DataTable({
    responsive: true,
    pagingType: 'full_numbers',
    order: [[1, 'asc']]
  });
});

function create_button() {
  $('#modalTitle').text('Tambah Kriteria');
  $('#formKriteria').attr('action', '{{ route('kriteria.store') }}');
  $('#formKriteria input[name=_method]').remove();

  $('#formKriteria input[name=id]').val('');
  $('#formKriteria input[name=kode]').val('');
  $('#formKriteria input[name=kriteria]').val('');
  $('#formKriteria select[name=atribut]').val('');
  $('#formKriteria input[name=urutan_prioritas]').val('');
  $('#formKriteria input[name=bobot_roc]').val('');
}

function show_button(kriteria_id) {
  $('#modalTitle').text('Edit Kriteria');
  $('#formKriteria').attr('action', '{{ route('kriteria.update') }}');

  // pastikan metode POST (controller.update menerima POST /update)
  if (!$('#formKriteria input[name=_method]').length) {
    $('#formKriteria').append('<input type="hidden" name="_method" value="POST">');
  }

  $('#btnSubmit').prop('disabled', true).text('Memuat...');

  $.ajax({
    type: 'GET',
    url: '{{ route('kriteria.edit') }}',
    data: {
      _token: '{{ csrf_token() }}',
      kriteria_id: kriteria_id
    },
    success: function (d) {
      $('#formKriteria input[name=id]').val(d.id);
      $('#formKriteria input[name=kode]').val(d.kode);
      $('#formKriteria input[name=kriteria]').val(d.kriteria);
      $('#formKriteria select[name=atribut]').val(d.atribut);
      $('#formKriteria input[name=urutan_prioritas]').val(d.urutan_prioritas);
      $('#formKriteria input[name=bobot_roc]').val(d.bobot_roc ?? '');
    },
    error: function () {
      alert('Gagal memuat data kriteria.');
    },
    complete: function () {
      $('#btnSubmit').prop('disabled', false).text('Simpan');
    }
  });
}
</script>
@endsection
