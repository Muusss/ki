@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $title ?? 'Data Permintaan' }}</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="openCreate()">
    Tambah Permintaan
  </button>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblPermintaan" class="table table-striped table-bordered w-100 datatable">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Komposisi</th>
            <th>Harga</th>
            <th>SPF</th>
            <th style="width: 140px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse (($permintaan ?? []) as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $row->nama_produk }}</strong></td>
            <td class="text-break">{{ $row->komposisi }}</td>
            <td>
              @if($row->harga === '<50k')
                &lt;50k
              @elseif($row->harga === '50-100k')
                50–100k
              @else
                &gt;100k
              @endif
            </td>
            <td>{{ $row->spf }}</td>
            <td class="text-nowrap">
              <button class="btn btn-sm btn-warning"
                      data-bs-toggle="modal"
                      data-bs-target="#modalForm"
                      onclick="openEdit({{ $row->id }}, @js($row->nama_produk), @js($row->komposisi), @js($row->harga), @js($row->spf))">
                Edit
              </button>

              <form action="{{ route('permintaan.destroy', $row->id) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Hapus permintaan {{ $row->nama_produk }} ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada data permintaan.</td>
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
      <form id="formPermintaan" method="POST" action="{{ route('permintaan.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Permintaan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-control" name="nama_produk" required maxlength="100" placeholder="Nama Produk">
            </div>
            <div class="col-md-6">
              <label class="form-label">Harga</label>
              <select class="form-select" name="harga" required>
                <option value="" disabled selected>Pilih rentang harga</option>
                <option value="<50k">&lt;50k</option>
                <option value="50-100k">50-100k</option>
                <option value=">100k">&gt;100k</option>
              </select>
            </div>
            <div class="col-md-8">
              <label class="form-label">Komposisi</label>
              <textarea class="form-control" name="komposisi" rows="3" required placeholder="Tulis komposisi di sini"></textarea>
            </div>
            <div class="col-md-4">
              <label class="form-label">SPF</label>
              <select class="form-select" name="spf" required>
                <option value="" disabled selected>Pilih SPF</option>
                <option value="30">30</option>
                <option value="35">35</option>
                <option value="40">40</option>
                <option value="50+">50+</option>
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
/** DataTables (optional) */
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

/** Helpers: buka modal Create / Edit tanpa AJAX */
function resetForm() {
  const f = $('#formPermintaan');
  f.trigger('reset');
  f.attr('action', '{{ route('permintaan.store') }}');
  // hapus _method jika ada
  f.find('input[name=_method]').remove();
}

function openCreate() {
  resetForm();
  $('#modalTitle').text('Tambah Permintaan');
  $('#btnSubmit').text('Simpan');
}

function openEdit(id, nama_produk, komposisi, harga, spf) {
  resetForm();
  $('#modalTitle').text('Edit Permintaan');

  // set action ke route update (PUT)
  let updateUrl = @json(route('permintaan.update', ':id'));
  updateUrl = updateUrl.replace(':id', id);
  const f = $('#formPermintaan');
  f.attr('action', updateUrl);
  f.append('<input type="hidden" name="_method" value="PUT">');

  // isi field
  f.find('input[name=nama_produk]').val(nama_produk);
  f.find('textarea[name=komposisi]').val(komposisi);
  f.find('select[name=harga]').val(harga);
  f.find('select[name=spf]').val(spf);
}
</script>
@endsection