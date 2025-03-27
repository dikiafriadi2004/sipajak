<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Laporan Pajak Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formTambah">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <label for="bulan" class="form-label text-capitalize">bulan</label>
                        <select id="bulan" name="bulan"  class="form-control form-control-sm" required>>
                            <option value="">-</option>
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
                    </div>
                    <div class="col">
                        <label for="tahun" class="form-label text-capitalize">Tahun</label>
                        <select id="tahun" name="tahun"  class="form-control form-control-sm" required>>
                            <option value="">-</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <label for="setoran" class="form-label text-capitalize">setoran</label>
                        <input type="text" class="form-control form-control-sm" id="setoran" maxlength="12" name="setoran" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="bukti" class="form-label">Bukti Laporan</label>
                    <input class="form-control form-control-sm" id="bukti" name="bukti" type="file">
                  </div>
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-floppy2-fill me-2"></i>
                    Simpan
                </button>
            </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalPemberitahuan" tabindex="-1" aria-labelledby="modalPemberitahuanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPemberitahuanLabel">Edit Tanggal Surat Pemberitahuan (ID: <span id="displayId"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditPemberitahuan">
                    <input type="hidden" id="pemberitahuanId">
                    <div class="mb-3">
                        <label for="tgl_surat_pemberitahuan" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tgl_surat_pemberitahuan" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTeguran" tabindex="-1" aria-labelledby="modalTeguranLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTeguranLabel">Edit Tanggal Surat Teguran (ID: <span id="displayIdTeguran"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditTeguran">
                    <input type="hidden" id="teguranId">
                    <div class="mb-3">
                        <label for="tgl_surat_teguran" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tgl_surat_teguran" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
