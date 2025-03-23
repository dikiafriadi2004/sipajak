<div class="mb-3">
    <input type="hidden" class="form-control" name="npwpd" value="{{ $data['pajak']->npwpd }}" disabled>
</div>

<div class="mb-3">
    <input type="hidden" name="nama_pemilik" value="{{ $data['pajak']->nama_pemilik }}">
</div>

<div class="mb-3">
    <input type="hidden" name="no_hp" value="{{ $data['pajak']->no_hp }}">
</div>

<div class="mb-3">
    <input type="hidden" name="nama_usaha" value="{{ $data['pajak']->nama_usaha }}">
</div>

<div class="mb-3">
    <input type="hidden" name="alamat_usaha" value="{{ $data['pajak']->alamat_usaha }}">
</div>

<div class="mb-3">
    <input type="hidden" name="tgl_surat_pemberitahuan" class="form-control"
        value="{{ $data['pajak']->tgl_surat_pemberitahuan }}">
</div>
