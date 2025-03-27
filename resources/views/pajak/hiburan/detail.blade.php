@extends('layouts.app')

@push('css')

@endpush

@section('content')
<section class="laporan mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('pajakhiburan.index', []) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        kembali
                    </a>
                </div>
                <div class="">
                    <h2 class="fw-bold text-capitalize">
                        Detail Laporan Pajak {{ $pajak->nama_pemilik }}
                    </h2>
                    <h4 class="fw-bold">NPWPD: {{ $pajak->npwpd }}</h4>
                </div>

                <div class="mt-4">
                    Alamat : {{ $pajak->alamat_usaha }}
                    <br>
                    Kontak : {{ $pajak->no_hp }}
                </div>

                <!-- Form Filter -->
                <div class="d-flex gap-2 justify-content-end mt-4 mb-3">
                    <input type="search" class="form-control form-control-sm w-25" id="searchLaporan" placeholder="Cari Laporan...">

                    <select id="filterBulan" class="form-control form-control-sm w-25">
                        <option value="">Pilih Bulan</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                    </select>

                    <select id="filterTahun" class="form-control form-control-sm w-25">
                        <option value="">Pilih Tahun</option>
                        @for ($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>

                    <a href="#" class="btn btn-sm btn-primary btn-tooltips"
                        data-bs-toggle="modal" data-bs-target="#modalTambah"
                        data-bs-original-title="Tambah Laporan Pajak ">
                        <i class="bi bi-plus-lg me-2"></i> Tambah
                    </a>
                </div>

                <div class="table-responsive table-borderless mt-3" id="Datalaporan" data-id="{{ $pajak->id }}">

                </div>

                {{-- PAGINATE --}}
                <div class="d-flex justify-content-end mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Tombol Previous --}}
                            @if ($laporan->onFirstPage())
                            <li class="page-item disabled"><a class="page-link">Previous</a></li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="#" data-page="{{ $laporan->currentPage() - 1 }}">Previous</a>
                            </li>
                            @endif

                            {{-- Halaman Pertama (Selalu Ditampilkan) --}}
                            @if ($laporan->currentPage() > 2)
                            <li class="page-item">
                                <a class="page-link" href="#" data-page="1">1</a>
                            </li>
                            @if ($laporan->currentPage() > 3)
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                            @endif
                            @endif

                            {{-- Halaman Sebelum, Saat Ini, dan Sesudah --}}
                            @for ($i = max(1, $laporan->currentPage() - 1); $i <= min($laporan->lastPage(), $laporan->currentPage() + 1); $i++)
                                <li class="page-item {{ $laporan->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Halaman Terakhir (Selalu Ditampilkan) --}}
                            @if ($laporan->currentPage() < $laporan->lastPage() - 1)
                                @if ($laporan->currentPage() < $laporan->lastPage() - 2)
                                <li class="page-item disabled"><a class="page-link">...</a></li>
                                @endif
                                <li class="page-item">
                                <a class="page-link" href="#" data-page="{{ $laporan->lastPage() }}">{{ $laporan->lastPage() }}</a>
                                </li>
                            @endif

                            {{-- Tombol Next --}}
                            @if ($laporan->hasMorePages())
                                <li class="page-item">
                                <a class="page-link" href="#" data-page="{{ $laporan->currentPage() + 1 }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled"><a class="page-link">Next</a></li>
                            @endif
                        </ul>
                    </nav>
                </div>

            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div>
</section>
@include('pajak.hiburan.modal-laporan')
@endsection

@push('js')
<script>
    // LOAD DATA
    $(document).ready(function() {
        var hiburanId = $("#Datalaporan").data("id");
        loadLaporan(hiburanId);
    });

    function loadLaporan(id, page = 1) {
        $.ajax({
            url: "{{ route('laporan.hiburan.data', ':id') }}".replace(':id', id) + "?page=" + page,
            type: "GET",
            success: function (response) {
                $("#Datalaporan").html(response); // Ganti isi #Datalaporan dengan data baru
                $('[data-bs-toggle="tooltip"]').tooltip(); // Aktifkan kembali tooltip jika ada
            },
            error: function () {
                $("#Datalaporan").html('<p class="text-center text-danger">Gagal mengambil data</p>');
            }
        });
    }

    // PAGINATION
    $(document).on("click", ".pagination a", function (event) {
        event.preventDefault();

        let page = $(this).data("page"); // Ambil nomor halaman dari href
        let id = $("#Datalaporan").data("id"); // Ambil ID hiburan

        console.log("ID:", id);  // Cek apakah ID ditemukan
        console.log("Page:", page); // Cek apakah page ditemukan

        if (!page || !id) {
            console.error("ID atau page tidak ditemukan.");
            return;
        }

        loadLaporan(id, page);
    });

    $(document).ready(function () {
        // Tangkap klik pada tombol dengan class btn-tooltips
        $('.btn-tooltips').on('click', function () {
            let id = $(this).data('id'); // Ambil data-id dari tombol yang diklik

            // Set ID ke dalam modal sebelum modal muncul
            $('#modalPemberitahuan .modal-body').html('ID: ' + id);
        });
    });



</script>

{{-- FILTER --}}
<script>
    $(document).ready(function () {
        function loadLaporan() {
            let search = $('#searchLaporan').val();
            let bulan = $('#filterBulan').val();
            let tahun = $('#filterTahun').val();
            let id = $("#Datalaporan").data("id");

            $.ajax({
                url: "{{ route('laporan.hiburan.filter', ':id') }}".replace(':id', id),
                method: "GET",
                data: { search: search, bulan: bulan, tahun: tahun, id: id },
                success: function (response) {
                    $('#Datalaporan').html(response);
                },
                error: function () {
                    $('#Datalaporan').html('<p class="text-center text-danger">Gagal mengambil data</p>');
                }
            });
        }

        // Event listener untuk pencarian dan filter
        $('#searchLaporan, #filterBulan, #filterTahun').on('keyup change', function () {
            loadLaporan();
        });
    });
</script>

{{-- STORE --}}
<script>
    $(document).ready(function () {
        $("#formTambah").submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let id = $("#Datalaporan").data("id"); // ID Pajak hiburan
            formData.append("id", id);

            $.ajax({
                url: "{{ route('laporan.hiburan.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#formTambah button[type='submit']").prop("disabled", true).text("Menyimpan...");
                },
                success: function (response) {
                    $("#modalTambah").modal("hide");
                    $('.modal-backdrop').remove();
                    $("#formTambah")[0].reset();
                    loadLaporan(id);
                    Swal.fire("Sukses!", "Laporan berhasil ditambahkan.", "success");
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    $.each(errors, function (key, value) {
                        errorMessages += value[0] + "<br>";
                    });

                    Swal.fire("Error!", errorMessages, "error");
                },
                complete: function () {
                    $("#formTambah button[type='submit']").prop("disabled", false).text("Simpan");
                },
            });
        });
    });
</script>

{{-- // TOOLTIPS --}}
<script>
    $(document).ready(function () {
        $('[data-tooltip]').each(function () {
            var tooltipText = $(this).attr('data-tooltip');
            $(this).tooltip({
                title: tooltipText,
                placement: 'top',
                animation: true,
                delay: { "show": 200, "hide": 100 }
            });
        });

        $('.custom-tooltip').on('click', function () {
            var targetModal = $(this).attr('data-bs-target');
            $(targetModal).modal('show');
        });
    });
</script>

{{-- PEMBERITAHUAN --}}
<script>
    $(document).on('click', '.btn-tooltips', function () {
        let id = $(this).data('id');
        $('#pemberitahuanId').val(id);
        $('#displayId').text(id); // Tampilkan ID di modal

        // Ambil data berdasarkan ID (AJAX GET)
        $.ajax({
            url: "{{ route('laporan.hiburan.pemberitahuan.show', '') }}/" + id,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    $('#tgl_surat_pemberitahuan').val(response.data.tgl_surat_pemberitahuan);
                } else {
                    alert("Data tidak ditemukan");
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat mengambil data");
            }
        });
    });

    // Submit form edit dengan metode PUT
    $('#formEditPemberitahuan').on('submit', function (e) {
        e.preventDefault();
        let id = $('#pemberitahuanId').val(); // Ambil ID dari input hidden
        let tgl_surat_pemberitahuan = $('#tgl_surat_pemberitahuan').val();

        $.ajax({
            url: "{{ route('laporan.hiburan.pemberitahuan', ':id') }}".replace(':id', id),
            type: "PUT",
            data: {
                id: id, // Kirim ID dalam data juga
                tgl_surat_pemberitahuan: tgl_surat_pemberitahuan,
                _token: "{{ csrf_token() }}" // Tambahkan CSRF Token
            },
            success: function (response) {
                if (response.success) {
                    alert("Data berhasil diperbarui");
                    $('#modalPemberitahuan').modal('hide');
                    location.reload();
                } else {
                    alert("Gagal memperbarui data");
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert("Terjadi kesalahan saat memperbarui data");
            }
        });
    });
</script>

{{-- TEGURAN --}}
<script>
    $(document).on('click', '.btn-tooltips', function () {
        let id = $(this).data('id');
        $('#teguranId').val(id); // Gunakan nama yang benar (harus sesuai input hidden)
        $('#displayIdTeguran').text(id); // Tampilkan ID di modal

        // Ambil data berdasarkan ID (AJAX GET)
        $.ajax({
            url: "{{ route('laporan.hiburan.teguran.show', ':id') }}".replace(':id', id),
            type: "GET",
            success: function (response) {
                if (response.success) {
                    $('#tgl_surat_teguran').val(response.data.tgl_surat_teguran);
                } else {
                    alert("Data tidak ditemukan");
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat mengambil data");
            }
        });
    });

    // Submit form edit dengan metode PUT
    $('#formEditTeguran').on('submit', function (e) {  // Nama form harus benar
        e.preventDefault();

        let id = $('#teguranId').val(); // Gunakan nama ID yang benar
        let tgl_surat_teguran = $('#tgl_surat_teguran').val();

        $.ajax({
            url: "{{ route('laporan.hiburan.teguran', ':id') }}".replace(':id', id),
            type: "PUT",
            data: {
                tgl_surat_teguran: tgl_surat_teguran,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.success) {
                    alert("Data berhasil diperbarui");
                    $('#modalTeguran').modal('hide');
                    location.reload();
                } else {
                    alert("Gagal memperbarui data");
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert("Terjadi kesalahan saat memperbarui data");
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
