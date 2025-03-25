<table class="table table-hover mb-0">
  <thead class="bg-light bg-opacity-50">
    <tr>
      <th class="text-center" style="width: 5%">No</th>
      <th class="" style="width: 15%">NPWD</th>
      <th class="" style="width: 15%">Pemilik</th>
      <th class="" style="width: 10%">Usaha</th>
      <th class="" style="width: 15%">Kontak</th>
      <th class="">Alamat Usaha</th>
      <th class="text-center" style="width: 11%">Action</th>
    </tr>
  </thead>
    <tbody>
      @php
          $no = ($pajakhotel->currentPage() - 1) * $pajakhotel->perPage() + 1;
      @endphp
      @foreach ($pajakhotel as $item)
        <tr>
          <td>
              <p class="text-center">{{ $no++ }}</p>
          </td>
          <td>
            <p class="">{{ $item->npwpd }}</p>
          </td>
          <td>
            <p class="">{{ $item->nama_pemilik }}</p>
          </td>
          <td>
            <p class="">{{ $item->nama_usaha }}</p>
          </td>
          <td>
            <p class="">{{ $item->no_hp }}</p>
          </td>
          <td>
            <p class="">{{ $item->alamat_usaha }}</p>
          </td>
          <td class="text-center">
            <a href="javascript:void(0)" class="btn btn-sm btn-warning btn-edit"
              data-id="{{ $item->id }}" data-bs-toggle="tooltip" title="Edit Data">
              <i class='bx bxs-pencil' style="font-size: 1rem"></i>
            </a>
            <a href="{{ route('laporan.hotel.index', ['id'=> $item->id]) }}" class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Lihat Detail Pajak">
              <i class='bx bx-bullseye' style="font-size: 1rem"></i>
            </a>
            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}"
              data-bs-toggle="tooltip" title="Hapus Data Pajak">
              <i class='bx bxs-trash' style="font-size: 1rem"></i>
            </button>
          </td>
        </tr>
      @endforeach
    </tbody>
</table> <!-- end table -->

<script>
  $(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip(); // Aktifkan tooltip di semua elemen dengan atribut ini
  });
</script>
