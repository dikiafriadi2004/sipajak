<?php

namespace App\Http\Controllers\Pajak;

use App\Http\Controllers\Controller;
use App\Models\Pajak\PajakRestoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PajakRestoranController extends Controller
{
    public function index()
    {
        $pajakrestoran = PajakRestoran::orderBy('created_at', 'desc')
        ->paginate(10);
        return view('pajak.restoran.index',compact('pajakrestoran'));
    }

    public function getData()
    {
        $pajakrestoran = PajakRestoran::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pajak.restoran.data', compact('pajakrestoran'));
    }

    public function getfilter(Request $request)
    {
        $search = $request->input('search'); // Ambil query pencarian

        $pajakrestoran = PajakRestoran::where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->where('npwpd', 'like', "%$search%")
                    ->orWhere('nama_pemilik', 'like', "%$search%")
                    ->orWhere('nama_usaha', 'like', "%$search%");
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('pajak.restoran.data', compact('pajakrestoran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npwpd' => 'required|unique:pajak_restoran,npwpd',
            'nama_pemilik' => 'required',
            'nama_usaha' => 'required',
            'no_hp' => 'required',
            'alamat_usaha' => 'required',
        ]);

        PajakRestoran::create([
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
        $pajak = PajakRestoran::findOrFail($id);
        $pajak->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }

    public function edit($id)
    {
        $data = PajakRestoran::find($id);
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
        }
    }
    
    public function update(Request $request, $id)
    {
        $data = PajakRestoran::find($id);
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
        $data['pajak'] = PajakRestoran::find($id);
        $data['laporan'] = DB::table('laporan_pajak_restoran as lpr')
            ->where('pajak_restoran_id',$id)
            ->orderBy('created_at', 'desc')
        ->paginate(2);

        if (request()->ajax()) {
            return view('pajak.restoran.detail', compact('data'))->render();
        }
        return view('pajak.restoran.detail', compact('data'));
    }
}
