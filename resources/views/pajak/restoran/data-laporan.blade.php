<table class="table" id="laporan-table">
    <thead class="bg-light bg-opacity-50">
        <tr>
            <th class="" style="width: 5%">No</th>
            <th class="" style="width: 8%">Bulan</th>
            <th class="" style="width: 8%">Tahun</th>
            <th class="" style="width: 10%">Setoran</th>
            <th class=" text-center" style="width: 14%">Bukti Laporan</th>
            <th class=" text-center" style="width: 15%">Pemberitahuan</th>
            <th class=" text-center" style="width: 15%">Teguran</th>
            <th class=" text-center" style="width: 10%">Buat Surat</th>
        </tr>
    </thead> <!-- end thead-->
    <tbody id="laporan-table">
        @php
            $no = ($laporan->currentPage() - 1) * $laporan->perPage() + 1;
        @endphp
        @foreach ($laporan as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->bulan }}</td>
                <td>{{ $item->tahun }}</td>
                <td>Rp {{ number_format((float) $item->jumlah_setoran, 0, ',', '.') }}</td>

                <td class="text-center">
                    @if ($item->bukti_laporan)
                        <a href="/storage/{{ $item->bukti_laporan }}" class="badge rounded-pill bg-success p-2"
                            target="_blank" data-bs-toggle="tooltip" title="Lihat Bukti Laporan"
                            style="text-decoration: none">
                            Lihat Berkas
                        </a>
                    @else
                        <p class="badge rounded-pill text-secondary p-2">
                            Tidak Ada Berkas
                        </p>
                    @endif
                </td>
                <td style="text-align:center;">
                    @if ($item->tgl_surat_pemberitahuan != null)
                    <form action="{{ route('laporan.restoran.teguran.download', $item->id) }}" method="POST">
                        @include('surat.restoran.download-surat-pemberitahuan')
                        <a href="{{ route('laporan.restoran.teguran.download', $item->id) }}" class="badge rounded-pill bg-success p-2" style="text-decoration: none"
                            target="_blank" data-bs-toggle="tooltip" title="Download Surat Pemberitahuan">
                            Download
                        </a>
                    </form>
                    @else
                        <div class="badge rounded-pill text-secondary p-2 ">
                            Tidak Ada Surat
                        </div>
                    @endif
                </td>
                <td style="text-align:center;">
                    @if ($item->tgl_surat_teguran != null)
                        <a href="" class="badge rounded-pill bg-success p-2" style="text-decoration: none"
                            target="_blank" data-bs-toggle="tooltip" title="Download Surat Teguran">
                            Download
                        </a>
                    @else
                        <div class="badge rounded-pill text-secondary p-2">
                            Tidak Ada Surat
                        </div>
                    @endif
                </td>
                <td class="d-flex gap-2 justify-content-center h-100">
                    <div class="text-center">
                        <a href="javascript:void(0)" class="btn btn-sm btn-warning btn-tooltips" data-bs-toggle="modal" data-bs-tooltip="tooltip"
                            data-bs-target="#modalPemberitahuan" title="Tambah Surat Pemberitahuan"
                            data-id="{{ $item->id }}">
                            <i class='bx bx-envelope'></i>
                        </a>
                    </div>
                    <div class="text-center">
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger btn-tooltips" data-bs-toggle="modal" data-bs-tooltip="tooltip"
                            data-bs-target="#modalTeguran" title="Tambah Surat Teguran" data-id="{{ $item->id }}">
                            <i class='bx bxs-envelope'></i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody> <!-- end tbody -->
</table>


<script>
    $(document).ready(function() {
        $('[data-bs-tooltip="tooltip"]').tooltip(); // Aktifkan tooltip di semua elemen dengan atribut ini
    });
</script>
