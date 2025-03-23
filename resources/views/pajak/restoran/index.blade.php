@extends('layouts.app')

@push('css')
  {{-- <link rel="stylesheet" href="{{ asset('css/footer.css') }}"> --}}
@endpush

@section('content')

<section class="pajak mt-3">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        @if (Session::has('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
          </div>
        @endif
        <h2 class="fw-bold">Pajak Restoran</h2>
        <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
          <div class="search-bar">
            <span><i class="bx bx-search-alt"></i></span>
            <input type="search" class="form-control form-control-sm" id="search" name="search" placeholder="Cari data...">
          </div>
          <div>
            <a href="#" class="btn btn-sm btn-primary btn-tambah"
              data-bs-toggle="modal" data-bs-target="#modalTambah"
              data-bs-original-title="Tambah Data">
              <i class="bi bi-plus-lg me-2"></i> Tambah
            </a>
          </div>
        </div>

        {{-- id="DataTable" digunakan untuk tempat menaruh data.blade nya --}}
        <div class="table-responsive table-centered" id="DataTable">
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-end mt-3">
          <nav aria-label="Page navigation">
            <ul class="pagination">
              {{-- Tombol Previous --}}
              @if ($pajakrestoran->onFirstPage())
                <li class="page-item disabled"><a class="page-link">Previous</a></li>
              @else
                <li class="page-item">
                  <a class="page-link" href="#" data-page="{{ $pajakrestoran->currentPage() - 1 }}">Previous</a>
                </li>
              @endif

              {{-- Halaman Pertama (Selalu Ditampilkan) --}}
              @if ($pajakrestoran->currentPage() > 2)
                <li class="page-item">
                  <a class="page-link" href="#" data-page="1">1</a>
                </li>
                @if ($pajakrestoran->currentPage() > 3)
                  <li class="page-item disabled"><a class="page-link">...</a></li>
                @endif
              @endif

                {{-- Halaman Sebelum, Saat Ini, dan Sesudah --}}
                @for ($i = max(1, $pajakrestoran->currentPage() - 1); $i <= min($pajakrestoran->lastPage(), $pajakrestoran->currentPage() + 1); $i++)
                  <li class="page-item {{ $pajakrestoran->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                  </li>
                @endfor

                {{-- Halaman Terakhir (Selalu Ditampilkan) --}}
                @if ($pajakrestoran->currentPage() < $pajakrestoran->lastPage() - 1)
                  @if ($pajakrestoran->currentPage() < $pajakrestoran->lastPage() - 2)
                    <li class="page-item disabled"><a class="page-link">...</a></li>
                  @endif
                  <li class="page-item">
                    <a class="page-link" href="#" data-page="{{ $pajakrestoran->lastPage() }}">{{ $pajakrestoran->lastPage() }}</a>
                  </li>
                @endif

                {{-- Tombol Next --}}
                @if ($pajakrestoran->hasMorePages())
                  <li class="page-item">
                    <a class="page-link" href="#" data-page="{{ $pajakrestoran->currentPage() + 1 }}">Next</a>
                  </li>
                @else
                  <li class="page-item disabled"><a class="page-link">Next</a></li>
                @endif
            </ul>
          </nav>
        </div>

      </div>
    </div> <!-- end card -->
</div>
@include('pajak.restoran.modal')
</section>
@endsection

@push('js')
<script>
  $(document).ready(function() {
    loadData(); // Panggil saat halaman selesai dimuat
  });

  // LOAD DATA
  function loadData(page = 1) {
    $.ajax({
      // load data melalui route
      url: "{{ route('pajakrestoran.data') }}?page=" + page,
      type: "GET",
      success: function (response) {
        $("#DataTable").html(response);
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      error: function () {
        $("#DataTable").html('<p class="text-center text-danger">Gagal mengambil data</p>');
      }
    });
  }

  // PAGINATION
  $(document).on("click", ".pagination a", function (event) {
    event.preventDefault();
    let page = $(this).data("page"); // Ambil nomor halaman
    loadData(page);
  });

  // TOOLTIPS
  $(document).ready(function () {
    $('.btn-tambah').tooltip({ trigger: 'hover' }); // Aktifkan tooltip manual
  });


</script>

{{-- SEARCH --}}
<script>
  $(document).ready(function () {
    // Event listener untuk pencarian
    $('#search').on('keyup', function () {
      let query = $(this).val();
      loadData(1, query); // Kirim query ke AJAX
    });

    // kirim data pencarian dengan AJAX agar realtime
    function loadData(page = 1, search = '') {
      $.ajax({
        url: "{{ route('pajakrestoran.filter') }}",
        type: "GET",
        data: { page: page, search: search }, // Kirim parameter pencarian
        success: function (response) {
          $("#DataTable").html(response);
          $('[data-bs-toggle="tooltip"]').tooltip();
        },
        error: function () {
          $("#DataTable").html('<p class="text-center text-danger">Gagal mengambil data</p>');
        }
      });
    }
  });
</script>

{{-- STORE --}}
<script>
  $(document).ready(function () {
    $('#formTambah').on('submit', function (e) {
      e.preventDefault(); // Mencegah reload halaman

      let formData = {
        npwpd: $('#npwpd').val(),
        nama_pemilik: $('#nama_pemilik').val(),
        nama_usaha: $('#nama_usaha').val(),
        no_hp: $('#no_hp').val(),
        alamat_usaha: $('#alamat_usaha').val(),
        _token: $('input[name="_token"]').val()
      };

      $.ajax({
        url: "{{ route('pajakrestoran.store') }}",
        type: "POST",
        data: formData,
        success: function (response) {
          $('#modalTambah').modal('hide'); // Tutup modal
          $('.modal-backdrop').remove();
          $('#formTambah')[0].reset(); // Reset form
          loadData(); // Panggil loadData() untuk update tabel
        },
        error: function (xhr) {
          alert('Gagal menambahkan data!');
          console.log(xhr.responseText); // Debug jika error
        }
      });
    });
  });
</script>

{{-- DELETE --}}
<script>
  $(document).on('click', '.btn-delete', function () {
      let id = $(this).data('id'); // Ambil ID dari tombol
      let row = $(this).closest('tr'); // Cari baris terdekat dengan tombol

      if (confirm("Apakah kamu yakin ingin menghapus data ini?")) {
          $.ajax({
              url: "{{ route('pajakrestoran.destroy', ':id') }}".replace(':id', id),
              type: "DELETE",
              data: {
                  _token: "{{ csrf_token() }}"
              },
              success: function (response) {
                alert("Data berhasil dihapus!");
                row.fadeOut(500, function () { 
                    $(this).remove();
                    $('[data-bs-toggle="tooltip"]').tooltip(); // Refresh tooltip
                });
              },
              error: function (xhr) {
                  alert("Gagal menghapus data!");
                  console.log(xhr.responseText); // Debugging jika ada error
              }
          });
      }
  });
</script>

{{-- UDPATE --}}
<script>
$(document).ready(function () {
    // Load modal edit
    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        
        $.ajax({
            url: "{{ route('pajakrestoran.edit', ':id') }}".replace(':id', id), 
            type: "GET",
            success: function (response) {
                if (response.success) {
                    $('#edit_id').val(response.data.id);
                    $('#edit_npwpd').val(response.data.npwpd);
                    $('#edit_nama_pemilik').val(response.data.nama_pemilik);
                    $('#edit_nama_usaha').val(response.data.nama_usaha);
                    $('#edit_no_hp').val(response.data.no_hp);
                    $('#edit_alamat_usaha').val(response.data.alamat_usaha);
                    $('#modalEdit').modal('show');
                } else {
                    alert('Data tidak ditemukan!');
                }
            },
            error: function () {
                alert('Gagal mengambil data.');
            }
        });
    });

    // Submit form edit
    $('#formEdit').on('submit', function (e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = $(this).serialize();

        $.ajax({
            url: "{{ route('pajakrestoran.update', ':id') }}".replace(':id', id), 
            type: "PUT",
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#modalEdit').modal('hide'); // Tutup modal
                    loadData(); // Refresh data
                    alert('Data berhasil diperbarui!');
                } else {
                    alert('Gagal memperbarui data.');
                }
            },
            error: function () {
                alert('Terjadi kesalahan, coba lagi.');
            }
        });
    });
});


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush