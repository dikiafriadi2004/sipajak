<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTambahLabel">Tambah Data Pajak Hiburan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formTambah">
                @csrf
                <div class="row mb-3">
                    <div class="col">
                        <label for="npwpd" class="form-label">NPWPD</label>
                        <input type="text" class="form-control" id="npwpd" name="npwpd" required>
                    </div>
                    <div class="col">
                        <label for="nama_pemilik" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="nama_usaha" class="form-label">Nama Usaha</label>
                        <input type="text" class="form-control" id="nama_usaha" name="nama_usaha" required>
                    </div>
                    <div class="col">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="alamat_usaha" class="form-label">Alamat Usaha</label>
                    <textarea class="form-control" id="alamat_usaha" name="alamat_usaha" required></textarea>
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

<!-- Modal Edit Data -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalEditLabel">Edit Data Pajak Hiburan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">

                    <div class="row mb-3">
                        <div class="col">
                            <label for="edit_npwpd" class="form-label">NPWPD</label>
                            <input type="text" class="form-control" id="edit_npwpd" name="npwpd" required>
                        </div>
                        <div class="col">
                            <label for="edit_nama_pemilik" class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control" id="edit_nama_pemilik" name="nama_pemilik" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="edit_nama_usaha" class="form-label">Nama Usaha</label>
                            <input type="text" class="form-control" id="edit_nama_usaha" name="nama_usaha" required>
                        </div>
                        <div class="col">
                            <label for="edit_no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" id="edit_no_hp" name="no_hp" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_alamat_usaha" class="form-label">Alamat Usaha</label>
                        <textarea class="form-control" id="edit_alamat_usaha" name="alamat_usaha" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-floppy2-fill me-2"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

