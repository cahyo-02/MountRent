<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;

class DendaController extends Controller
{

    public function index()
    {
        $dendas = Denda::with('transaksi.user')
            ->latest()
            ->paginate(10);

        return view('admin.denda.index', compact('dendas'));
    }



    public function store(Request $request)
    {

        $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'jenis' => 'required',
            'keterangan' => 'nullable',
            'jumlah' => 'required|numeric|min:0',
            'detail_id' => 'nullable|exists:detail_transaksis,id'
        ]);

        Denda::create([
            'transaksi_id' => $request->transaksi_id,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'status' => 'belum_dibayar'
        ]);

        // jika barang hilang kurangi jumlah
        if ($request->jenis == 'hilang' && $request->detail_id) {

            $detail = \App\Models\DetailTransaksi::find($request->detail_id);

            if ($detail && $detail->jumlah > 0) {

                $detail->jumlah -= 1;
                $detail->save();
            }
        }

        return back()->with('success', 'Denda berhasil ditambahkan');
    }



    public function lunas(Denda $denda)
    {

        $denda->update([
            'status' => 'lunas'
        ]);

        return back()->with('success', 'Denda berhasil ditandai lunas');
    }

    public function show($id)
    {
        // Mengambil data denda beserta relasi transaksi dan usernya
        $denda = Denda::with(['transaksi.user', 'transaksi.details.barang'])->findOrFail($id);

        return view('admin.denda.detail', compact('denda'));
    }
}
