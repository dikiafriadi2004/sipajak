<?php

namespace App\Http\Controllers\Pajak;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Pajak\PajakHotel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PajakHotelController extends Controller
{
    public function index()
    {
        $pajakhotel = PajakHotel::orderBy('created_at', 'desc')
        ->paginate(10);
        return view('pajak.hotel.index',compact('pajakhotel'));
    }

    public function getData()
    {
        $pajakhotel = PajakHotel::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('pajak.hotel.data', compact('pajakhotel'));
    }

    public function getfilter(Request $request)
    {
        $search = $request->input('search'); // Ambil query pencarian

        $pajakhotel = PajakHotel::where(function ($query) use ($search) {
            if (!empty($search)) {
                $query->where('npwpd', 'like', "%$search%")
                    ->orWhere('nama_pemilik', 'like', "%$search%")
                    ->orWhere('nama_usaha', 'like', "%$search%");
            }
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return view('pajak.hotel.data', compact('pajakhotel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npwpd' => 'required|unique:pajak_hotel,npwpd',
            'nama_pemilik' => 'required',
            'nama_usaha' => 'required',
            'no_hp' => 'required',
            'alamat_usaha' => 'required',
        ]);

        PajakHotel::create([
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
        $pajak = PajakHotel::findOrFail($id);
        $pajak->delete();

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }

    public function edit($id)
    {
        $data = PajakHotel::find($id);
        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function update(Request $request, $id)
    {
        $data = PajakHotel::find($id);
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
        $data['pajak'] = PajakHotel::find($id);
        $data['laporan'] = DB::table('laporan_pajak_hotel as lpr')
            ->where('pajak_hotel_id',$id)
            ->orderBy('created_at', 'desc')
        ->paginate(2);

        if (request()->ajax()) {
            return view('pajak.hotel.detail', compact('data'))->render();
        }
        return view('pajak.hotel.detail', compact('data'));
    }
}
