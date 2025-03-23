<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Laporan\LaporanRestoran;
use App\Models\Pajak\PajakRestoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporanRestoranController extends Controller
{
    public function index($id)
    {
        $pajak = PajakRestoran::find($id);
        $laporan = DB::table('laporan_pajak_restoran as lpr')
            ->where('pajak_restoran_id',$id)
            ->orderBy('created_at', 'desc')
        ->paginate(2);

        return view('pajak.restoran.detail', compact('pajak','laporan'));
    }

    public function getData($id)
    {
        $laporan = DB::table('laporan_pajak_restoran as lpr')
            ->where('pajak_restoran_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(2);
    
        if (request()->ajax()) {
            return view('pajak.restoran.data-laporan', compact('laporan', 'id'))->render();
        }
    
        return view('pajak.restoran.data-laporan', compact('laporan', 'id'));
    }
    
    public function getfilter(Request $request,$id)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $id = $request->input('id'); // ID Pajak Restoran
    
        $query = DB::table('laporan_pajak_restoran')
            ->where('pajak_restoran_id', $id);
    
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
    
        return view('pajak.restoran.data-laporan', compact('laporan'))->render();
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bulan' => 'required|string',
            'tahun' => 'required|numeric',
            'setoran' => 'required|numeric',
            'bukti_laporan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:512',
            'id' => 'required|exists:pajak_restoran,id',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('bukti_laporan', 'public');
        }
    
        $laporan = LaporanRestoran::create([
            'pajak_restoran_id' => $request->id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'jumlah_setoran' => $request->setoran,
            'bukti_laporan' => $buktiPath,
        ]);
    
        return response()->json(['message' => 'Laporan berhasil disimpan', 'data' => $laporan]);
    }

    public function showPemberitahun($id)
    {
        $pemberitahuan = LaporanRestoran::find($id);
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
    
        LaporanRestoran::where('id', $request->id) // Cari berdasarkan ID
            ->update([
                'tgl_surat_pemberitahuan' => $request->tgl_surat_pemberitahuan,
                'updated_at' => now(),
            ]);
    
        return response()->json(['success' => 'Data berhasil diperbarui!']);
    }

    public function showTeguran($id)
    {
        $pemberitahuan = LaporanRestoran::find($id);
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
    
        $teguran = LaporanRestoran::findOrFail($id); // Pakai findOrFail supaya error lebih jelas
        $teguran->update([
            'tgl_surat_teguran' => $request->tgl_surat_teguran,
            'updated_at' => now(),
        ]);
    
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
    }

    // Download Surat Pemberitahuan dan Teguran
    public function downloadsuratpemberitahuan(Request $request, $id)
    {
        $pajakrestoran = PajakRestoran::find($id);
        $laporanpajakrestoran = LaporanRestoran::find($id);

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('surat/restoran/surat_pemberitahuan.docx', true);

        $nama_pemilik = $request->nama_pemilik;
        $nama_usaha = $request->nama_usaha;
        $alamat_usaha = $request->alamat_usaha;
        $tgl_surat_pemberitahuan = $laporanpajakrestoran->tgl_surat_pemberitahuan;

        $templateProcessor->setValues(
            [
                'nama_pemilik' => $nama_pemilik,
                'nama_usaha' => $nama_usaha,
                'alamat_usaha' => $alamat_usaha,
                'tgl_surat_pemberitahuan' => $tgl_surat_pemberitahuan
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

        return view('surat.restoran.download-surat-pemberitahuan', compact('pajakrestoran', 'laporanpajakrestoran'));
    }
}
