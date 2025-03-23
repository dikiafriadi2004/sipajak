@extends('admin.layouts.app')

@section('title')
    Buat Surat Teguran Pajak Hiburan
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
                            Surat Teguran Pajak Hiburan
                        </h5>
                        <div class="mt-3">
                            <div>
                                <form action="{{ route('laporanpajakhiburan.suratteguran', $laporanpajakhiburan) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <span>Nama Pemilik : {{ $pajakhiburan->npwpd }}</span>
                                        <input type="hidden" class="form-control" name="npwpd" data-toggle="input-mask"
                                            data-mask-format="NP099.099.099.099.000" data-reverse="true"
                                            value="{{ $pajakhiburan->npwpd }}" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <span>Nama Pemilik : {{ $pajakhiburan->nama_pemilik }}</span>
                                        <input type="hidden" name="nama_pemilik"
                                            value="{{ $pajakhiburan->nama_pemilik }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Nomor Telepon : {{ $pajakhiburan->no_hp }}</span>
                                        <input type="hidden" name="no_hp" value="{{ $pajakhiburan->no_hp }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Nama Usaha : {{ $pajakhiburan->nama_usaha }}</span>
                                        <input type="hidden" name="nama_usaha" value="{{ $pajakhiburan->nama_usaha }}">
                                    </div>

                                    <div class="mb-3">
                                        <span>Alamat Usaha : {{ $pajakhiburan->alamat_usaha }}</span>
                                        <input type="hidden" name="alamat_usaha"
                                            value="{{ $pajakhiburan->alamat_usaha }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Tanggal Surat</label>
                                        <input type="text" id="humanfd-datepicker" name="tgl_surat_teguran"
                                            class="form-control" placeholder="Tanggal Surat Teguran"
                                            value="{{ $laporanpajakhiburan->tgl_surat_teguran }}">
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
