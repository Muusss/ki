@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $title ?? 'Data Produk Sunscreen' }}</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
    Tambah Produk
  </button>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblAlternatif" class="table table-striped table-bordered w-100 datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Kode</th>
            <th>Nama Produk</th>
            <th>Jenis Kulit</th>
            <th style="width:130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($alternatif as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><span class="badge bg-primary">{{ $row->kode_produk }}</span></td>
            <td><strong>{{ $row->nama_produk }}</strong></td>
            <td class="text-capitalize">{{ $row->jenis_kulit }}</td>
            <td class="text-nowrap">
              <button class="btn btn-sm btn-warning"
                      data-bs-toggle="modal" data-bs-target="#modalForm"
                      onclick="show_button({{ $row->id }})">
                Edit
              </button>

              <form action="{{ route('alternatif.delete') }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Hapus produk {{ $row->nama_produk }} ?')">
                @csrf
                <input type="hidden" name="id" value="{{ $row->id }}">
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data produk.</td>
          </tr>
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
      <form id="formAlternatif" method="POST" action="{{ route('alternatif.store') }}">
        @csrf
        <input type="hidden" name="id"> {{-- diisi saat edit --}}
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Kode Produk</label>
              <input type="text" class="form-control" name="kode_produk" required maxlength="50" placeholder="SPF001">
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-control" name="nama_produk" required maxlength="100" placeholder="Nama Sunscreen">
            </div>
            <div class="col-md-6">
              <label class="form-label">Jenis Kulit</label>
              <select class="form-select" name="jenis_kulit" required>
                <option value="" disabled selected>Pilih</option>
                <option value="normal">Normal</option>
                <option value="berminyak">Berminyak</option>
                <option value="kering">Kering</option>
                <option value="kombinasi">Kombinasi</option>
                <option value="sensitif">Sensitif</option>
              </select>
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
// Initialize DataTables globally (guarded: tidak re-init)
$(function () {
    $('.datatable').each(function () {
        if (!$.fn.DataTable.isDataTable(this)) {
            $(this).DataTable({
                responsive: true,
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
        }
    });
});


function create_button() {
  $('#modalTitle').text('Tambah Produk');
  $('#formAlternatif').attr('action', '{{ route('alternatif.store') }}');
  $('#formAlternatif input[name=_method]').remove();

  $('#formAlternatif input[name=id]').val('');
  $('#formAlternatif input[name=kode_produk]').val('');
  $('#formAlternatif input[name=nama_produk]').val('');
  $('#formAlternatif select[name=jenis_kulit]').val('');
}

function show_button(alternatif_id) {
  $('#modalTitle').text('Edit Produk');
  $('#formAlternatif').attr('action', '{{ route('alternatif.update') }}');
  if (!$('#formAlternatif input[name=_method]').length) {
    $('#formAlternatif').append('<input type="hidden" name="_method" value="POST">'); // rute update menerima POST
  }
  $('#btnSubmit').prop('disabled', true).text('Memuat...');

  $.ajax({
    type: 'GET',
    url: '{{ route('alternatif.edit') }}',
    data: { _token: '{{ csrf_token() }}', alternatif_id: alternatif_id },
    success: function (d) {
      $('#formAlternatif input[name=id]').val(d.id);
      $('#formAlternatif input[name=kode_produk]').val(d.kode_produk);
      $('#formAlternatif input[name=nama_produk]').val(d.nama_produk);
      $('#formAlternatif select[name=jenis_kulit]').val(d.jenis_kulit);
    },
    error: function () { alert('Gagal memuat data produk.'); },
    complete: function () { $('#btnSubmit').prop('disabled', false).text('Simpan'); }
  });
}
</script>
@endsection
