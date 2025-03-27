<?php

namespace App\Http\Controllers\Pajak;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Pajak\PajakHiburan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PajakHiburanController extends Controller
{
    public function index()
    {
        $pajakhiburan = PajakHiburan::orderBy('created_at', 'desc')
        ->paginate(10);
        return view('pajak.hiburan.index',compact('pajakhiburan'));
    }

    public function getData()
    {
        $pajakhiburan = PajakHiburan::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pajak.hiburan.data', compact('pajakhiburan'));
    }

    public function getfilter(Request $request)
    {
        $search = $request->input('search'); // Ambil query pencarian

        $pajakhiburan = PajakHiburan::where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->where('npwpd', 'like', "%$search%")
                    ->orWhere('nama_pemilik', 'like', "%$search%")
                    ->orWhere('nama_usaha', 'like', "%$search%");
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('pajak.hiburan.data', compact('pajakhiburan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npwpd' => 'required|unique:pajak_hiburan,npwpd',
            'nama_pemilik' => 'required',
            'nama_usaha' => 'required',
            'no_hp' => 'required',
            'alamat_usaha' => 'required',
        ]);

        PajakHiburan::create([
            'npwpd' => $request->npwpd,
            'nama_pemilik' => $request->nama_pemilik,
            'nama_usaha' => $request->nama_usaha,
            'no_hp' => $request->no_hp,
            'alamat_usaha' => $request->alamat_usaha,
            'created_at' => now(),
            'slug' => Str::slug($request->nama_pemilik . '-' . Str::random(5)),
        ]);

        return response()->json(['success' => 'Data berhasil ditambahkan!']);
    }

    public function destroy($id)
    {
        $pajak = PajakHiburan::findOrFail($id);
        $pajak->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }

    public function edit($id)
    {
        $data = PajakHiburan::find($id);
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = PajakHiburan::find($id);
        if (!$data) {
            return response()->json(['success' => false]);
        }

        $data->update([
            'npwpd' => $request->npwpd,
            'nama_pemilik' => $request->nama_pemilik,
            'nama_usaha' => $request->nama_usaha,
            'no_hp' => $request->no_hp,
            'alamat_usaha' => $request->alamat_usaha,
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $data['pajak'] = PajakHiburan::find($id);
        $data['laporan'] = DB::table('laporan_pajak_hiburan as lpr')
            ->where('pajak_hiburan_id',$id)
            ->orderBy('created_at', 'desc')
        ->paginate(2);

        if (request()->ajax()) {
            return view('pajak.hiburan.detail', compact('data'))->render();
        }
        return view('pajak.hiburan.detail', compact('data'));
    }
}
