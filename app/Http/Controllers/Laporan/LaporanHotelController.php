<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Models\Pajak\PajakHotel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Laporan\LaporanHotel;
use Illuminate\Support\Facades\Validator;

class LaporanHotelController extends Controller
{
    public function index($id)
    {
        $pajak = PajakHotel::find($id);
        $laporan = DB::table('laporan_pajak_hotel as lpr')
            ->where('pajak_hotel_id',$id)
            ->orderBy('created_at', 'desc')
        ->paginate(2);

        return view('pajak.hotel.detail', compact('pajak','laporan'));
    }

    public function getData($id)
    {
        $laporan = DB::table('laporan_pajak_hotel as lpr')
            ->where('pajak_hotel_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if (request()->ajax()) {
            return view('pajak.hotel.data-laporan', compact('laporan', 'id'))->render();
        }

        return view('pajak.hotel.data-laporan', compact('laporan', 'id'));
    }

    public function getfilter(Request $request,$id)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $id = $request->input('id'); // ID Pajak Hotel

        $query = DB::table('laporan_pajak_hotel')
            ->where('pajak_hotel_id', $id);

        if (!empty($bulan)) {
            $query->where('bulan', 'like', "%$bulan%");
        }

        if (!empty($tahun)) {
            $query->where('tahun', 'like', "%$tahun%");
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('bulan', 'like', "%$search%")
                  ->orWhere('tahun', 'like', "%$search%")
                  ->orWhere('jumlah_setoran', 'like', "%$search%");
            });
        }

        $laporan = $query->orderBy('created_at', 'desc')->paginate(2);

        return view('pajak.hotel.data-laporan', compact('laporan'))->render();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulan' => 'required|string',
            'tahun' => 'required|numeric',
            'setoran' => 'required|numeric',
            'bukti_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512',
            'id' => 'required|exists:pajak_hotel,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_laporan', 'public');
        }

        $laporan = LaporanHotel::create([
            'pajak_hotel_id' => $request->id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah_setoran' => $request->setoran,
            'bukti_laporan' => $buktiPath,
        ]);

        return response()->json(['message' => 'Laporan berhasil disimpan', 'data' => $laporan]);
    }

    public function showPemberitahun($id)
    {
        $pemberitahuan = LaporanHotel::find($id);
        if ($pemberitahuan) {
            return response()->json(['success' => true, 'data' => $pemberitahuan]);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }


    public function pemberitahuan(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tgl_surat_pemberitahuan' => 'required',
            'id' => 'required', // Pastikan ID ada di database
        ]);

        LaporanHotel::where('id', $request->id) // Cari berdasarkan ID
            ->update([
                'tgl_surat_pemberitahuan' => $request->tgl_surat_pemberitahuan,
                'updated_at' => now(),
            ]);

        return response()->json(['success' => 'Data berhasil diperbarui!']);
    }

    public function showTeguran($id)
    {
        $pemberitahuan = LaporanHotel::find($id);
        if ($pemberitahuan) {
            return response()->json(['success' => true, 'data' => $pemberitahuan]);
        }
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }


    public function teguran(Request $request, $id) // Pastikan ID diterima dari route
    {
        $request->validate([
            'tgl_surat_teguran' => 'required|date',
        ]);

        $teguran = LaporanHotel::findOrFail($id); // Pakai findOrFail supaya error lebih jelas
        $teguran->update([
            'tgl_surat_teguran' => $request->tgl_surat_teguran,
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    // Download Surat Pemberitahuan dan Teguran

    // DI FUCNTION BERIKAN ($id) UNTUK MENANGKAP DATA ID YANG DIKIRIM TADI
    public function downloadPemberitahuan($id) {
        // INI KODE UNTUK MEMBAUT QUERY RELASI MAS. DISINI SAYA MENGAMBIL DATA laporan_pajak_hotel DAN SAYA BERIKAN ALIAS lpr (ALIAS AGAR PENULISAN LEBIH SEDIKIT)
        $data = DB::table('laporan_pajak_hotel as lpr')
        // INI RELASINYA MAS, MEMAKAI LEFT JOIN UNTUK MENYAMBUNGKAN pajak_hotel KOLOM id DENGAN laporan_pajak_hotel KOLOM pajak_hotel_id
            ->leftJoin('pajak_hotel as pr', 'pr.id', '=', 'lpr.pajak_hotel_id')
            // INI MENCARI DATA BERDASARKAN laporan_pajak_hotel KOLOM id
            ->where('lpr.id', $id)
            // SELECT DIGUNAKAN UNTUK MENGAMBIL KOLOM YANG DINGINKAN CONTOH
            ->select([
                // SAYA MENGAMBIL DARI TABEL pajak_hotel (SAYA ALIAS SEBAGAI pr) KOLOM npwpd
                'pr.npwpd',
                'pr.nama_pemilik',
                'pr.no_hp',
                'pr.nama_usaha',
                'pr.alamat_usaha',
                // SAYA MENGAMBIL DARI TABEL laporan_pajak_hotel (SAYA ALIAS SEBAGAI lpr) KOLOM tgl_surat_pemberitahuan
                'lpr.tgl_surat_pemberitahuan',
                'lpr.jumlah_setoran'
                // JIKA INGIN MENAMBAHKAN DATA BARU TINGGAL KETIK NAMA_ALIAS.KOLOM, JANGAN LUPA BERI KOMA DI ATAS DAHULU
            ])
        ->first();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('surat/hotel/surat_pemberitahuan.docx', true);

        $nama_pemilik = $data->nama_pemilik;
        $nama_usaha = $data->nama_usaha;
        $alamat_usaha = $data->alamat_usaha;
        $tgl_surat_pemberitahuan = $data->tgl_surat_pemberitahuan;
        $jumlah_setoran = $data->jumlah_setoran;

        $templateProcessor->setValues(
            [
                'nama_pemilik' => $nama_pemilik,
                'nama_usaha' => $nama_usaha,
                'alamat_usaha' => $alamat_usaha,
                'tgl_surat_pemberitahuan' => $tgl_surat_pemberitahuan,
                'jumlah_setoran' => $jumlah_setoran,
            ]
        );

        $pathToSave = $nama_pemilik . '_' . $tgl_surat_pemberitahuan . '_' . 'surat_pemberitahuan.docx';
        $templateProcessor->saveAs($pathToSave);

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachement;filename="' . $pathToSave . '"');

        readfile($pathToSave);
        unlink($pathToSave);
    }

    public function downloadTeguran($id) {
        $data = DB::table('laporan_pajak_hotel as lpr')
            ->leftJoin('pajak_hotel as pr', 'pr.id', '=', 'lpr.pajak_hotel_id')
            ->where('lpr.id', $id)
            ->select([
                'pr.npwpd',
                'pr.nama_pemilik',
                'pr.no_hp',
                'pr.nama_usaha',
                'pr.alamat_usaha',
                'lpr.tgl_surat_teguran'
            ])
        ->first();

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('surat/hotel/surat_teguran.docx', true);

        $nama_pemilik = $data->nama_pemilik;
        $nama_usaha = $data->nama_usaha;
        $alamat_usaha = $data->alamat_usaha;
        $tgl_surat_teguran = $data->tgl_surat_teguran;

        $templateProcessor->setValues(
            [
                'nama_pemilik' => $nama_pemilik,
                'nama_usaha' => $nama_usaha,
                'alamat_usaha' => $alamat_usaha,
                'tgl_surat_teguran' => $tgl_surat_teguran
            ]
        );

        $pathToSave = $nama_pemilik . '_' . $tgl_surat_teguran . '_' . 'surat_teguran.docx';
        $templateProcessor->saveAs($pathToSave);

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachement;filename="' . $pathToSave . '"');

        readfile($pathToSave);
        unlink($pathToSave);
    }
}
