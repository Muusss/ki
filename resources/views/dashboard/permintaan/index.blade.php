@extends('dashboard.layouts.main')

@section('content')
@php
  // Paksa jadi collection biar aman kalau null
  $permintaan = collect($permintaan ?? []);
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $title ?? 'Data Permintaan' }}</h3>
</div>

@if (session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblPermintaan" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Komposisi</th>
            <th>Harga</th>
            <th>SPF</th>
            {{-- kolom ceklis --}}
            <th class="text-center" style="width:60px;">
              <input type="checkbox" id="checkAll" aria-label="Pilih semua">
            </th>
          </tr>
        </thead>
        <tbody>
        @forelse ($permintaan as $row)
          @php
            $id         = data_get($row, 'id');
            $nama       = data_get($row, 'nama_produk', '');
            $komposisi  = data_get($row, 'komposisi', '');
            $harga      = data_get($row, 'harga');
            $spf        = data_get($row, 'spf');

            $hargaLabel = $harga === '<50k' ? '&lt;50k' :
                          ($harga === '50-100k' ? '50–100k' :
                          ($harga === '>100k' ? '&gt;100k' : '-'));
          @endphp
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $nama }}</strong></td>
            <td class="text-break">{{ $komposisi }}</td>
            <td>{!! $hargaLabel !!}</td>
            <td>{{ $spf ?? '-' }}</td>
            <td class="text-center">
              <input type="checkbox"
                     class="form-check-input row-check"
                     name="selected[]"
                     value="{{ (int) $id }}"
                     aria-label="Pilih {{ $nama }}">
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

{{-- Modal Create / Edit (opsional, biarkan jika masih dipakai) --}}
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
/** ===== DataTables + Kolom Ceklis ===== */
$(function () {
  // Inisialisasi DataTable khusus tabel ini (hindari double init)
  if (!$.fn.DataTable.isDataTable('#tblPermintaan')) {
    const dt = $('#tblPermintaan').DataTable({
      responsive: true,
      columnDefs: [
        { orderable: false, targets: -1 } // kolom ceklis tidak bisa di-sort
      ],
      language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        paginate: { first:"Pertama", last:"Terakhir", next:"Selanjutnya", previous:"Sebelumnya" },
        emptyTable: "Tidak ada data"
      }
    });

    // Saat tabel redraw (pagination/sort/filter), sinkronkan status checkAll
    dt.on('draw', function () {
      const all = $('.row-check').length;
      const checked = $('.row-check:checked').length;
      $('#checkAll').prop('checked', all > 0 && checked === all);
    });
  }

  // Pilih semua
  $(document).on('change', '#checkAll', function () {
    $('.row-check').prop('checked', this.checked);
  });

  // Update checkAll jika ada perubahan per baris
  $(document).on('change', '.row-check', function () {
    const all = $('.row-check').length;
    const checked = $('.row-check:checked').length;
    $('#checkAll').prop('checked', all > 0 && checked === all);
  });
});

/** ===== Form helper (opsional, jika modal create masih dipakai) ===== */
function resetForm() {
  const f = $('#formPermintaan');
  f.trigger('reset');
  f.attr('action', @json(route('permintaan.store')));
  f.find('input[name=_method]').remove();
}
function openCreate() {
  resetForm();
  $('#modalTitle').text('Tambah Permintaan');
  $('#btnSubmit').text('Simpan');
}
</script>
@endsection
