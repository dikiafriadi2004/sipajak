@extends('admin.layouts.app')

@section('title')
    Buat Surat Pemberitahuan Pajak Hotel
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-1 anchor" id="basic">
                            Surat Pemberitahuan Pajak Hotel
                        </h5>
                        <div class="mt-3">
                            <div>
                                <form action="{{ route('laporanpajakhotel.suratpemberitahuan', $laporanpajakhotel) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <span>Nama Pemilik : {{ $pajakhotel->npwpd }}</span>
                                        <input type="hidden" class="form-control" name="npwpd" data-toggle="input-mask"
                                            data-mask-format="NP099.099.099.099.000" data-reverse="true"
                                            value="{{ $pajakhotel->npwpd }}" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <span>Nama Pemilik : {{ $pajakhotel->nama_pemilik }}</span>
                                        <input type="hidden" name="nama_pemilik"
                                            value="{{ $pajakhotel->nama_pemilik }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Nomor Telepon : {{ $pajakhotel->no_hp }}</span>
                                        <input type="hidden" name="no_hp" value="{{ $pajakhotel->no_hp }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Nama Usaha : {{ $pajakhotel->nama_usaha }}</span>
                                        <input type="hidden" name="nama_usaha" value="{{ $pajakhotel->nama_usaha }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Alamat Usaha : {{ $pajakhotel->alamat_usaha }}</span>
                                        <input type="hidden" name="alamat_usaha"
                                            value="{{ $pajakhotel->alamat_usaha }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Tanggal Surat</label>
                                        <input type="text" id="humanfd-datepicker" name="tgl_surat_pemberitahuan"
                                            class="form-control" placeholder="Tanggal Surat Teguran"
                                            value="{{ $laporanpajakhotel->tgl_surat_pemberitahuan }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
@endsection

@push('js')
    <script>
        document.getElementById('humanfd-datepicker').flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "d M Y",
        });
    </script>
@endpush
