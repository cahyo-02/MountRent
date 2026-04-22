<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangRusak;
use App\Models\Denda;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangRusakController extends Controller
{
    public function index()
    {
        $barangRusaks = BarangRusak::with('barang')
            ->latest()
            ->paginate(10);

        $barangs = Barang::where('keterangan', 'aktif')->get();

        return view('admin.barang_rusak.index', compact('barangRusaks', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required_without:detail_id|exists:barangs,id',
            'detail_id' => 'required_without:barang_id|exists:detail_transaksis,id',
            'jumlah'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
            'gambar.*'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'denda'     => 'nullable|numeric|min:0'
        ]);
        $barang = null;
        $detail = null;

        if ($request->filled('detail_id')) {
            $detail = DetailTransaksi::findOrFail($request->detail_id);
            $barang = $detail->barang;

            if ($detail->jumlah < $request->jumlah) {
                return back()->with('error', 'Jumlah rusak melebihi jumlah barang yang disewa.');
            }
        } else {
            $barang = Barang::findOrFail($request->barang_id);

            if ($barang->stok < $request->jumlah) {
                return back()->with('error', 'Stok gudang tidak mencukupi untuk dilaporkan rusak.');
            }
        }

        // PROSES MULTIPLE UPLOAD GAMBAR KERUSAKAN
        $paths = [];
        if ($request->hasFile('gambar')) {
            // Kita looping (putar) untuk menyimpan semua file yang diupload
            foreach ($request->file('gambar') as $file) {
                $paths[] = $file->store('barang_rusak', 'public');
            }
        }

        DB::transaction(function () use ($request, $barang, $detail, $paths) {
            BarangRusak::create([
                'barang_id'  => $barang->id,
                'detail_id'  => $detail ? $detail->id : null,
                'jumlah'     => $request->jumlah,
                'keterangan' => $request->keterangan,
                'gambar'     => $paths, // Menyimpan array path gambar
                'status'     => 'rusak'
            ]);

            if ($detail && $request->filled('denda') && $request->denda > 0) {
                \App\Models\Denda::create([
                    'transaksi_id' => $detail->transaksi_id,
                    'jenis'        => 'rusak',
                    'keterangan'   => $request->keterangan,
                    'jumlah'       => $request->denda,
                    'status'       => 'belum_dibayar'
                ]);
                $detail->jumlah -= $request->jumlah;
                $detail->save();
            }

            $barang->stok -= $request->jumlah;
            $barang->save();

            if (method_exists($barang, 'updateStokDitampilkan')) {
                $barang->updateStokDitampilkan();
            }
        });

        return back()->with('success', 'Barang rusak dicatat, stok dikurangi.');
    }

    // TAMBAHKAN METHOD SHOW INI
    public function show($id)
    {
        // Cari data barang rusak berdasarkan ID, sekalian tarik data relasi 'barang'-nya
        $barangRusak = BarangRusak::with('barang')->findOrFail($id);

        // Kirim variabel $barangRusak ke halaman view
        return view('admin.barang_rusak.show', compact('barangRusak'));
    }


    public function perbaiki($id)
    {
        $rusak = BarangRusak::findOrFail($id);

        if ($rusak->status !== 'rusak') {
            return back()->with('error', 'Barang sudah diproses');
        }

        $barang = $rusak->barang;

        // Gunakan DB Transaction agar aman
        DB::transaction(function () use ($rusak, $barang) {
            // 1. TAMBAH STOK BARANG UTAMA SEBANYAK 1 SAJA
            $barang->stok += 1;
            $barang->save();

            // UPDATE STOK DITAMPILKAN
            if (method_exists($barang, 'updateStokDitampilkan')) {
                $barang->updateStokDitampilkan();
            }

            // 2. PISAHKAN RECORD JIKA JUMLAH > 1
            if ($rusak->jumlah > 1) {
                // Kurangi jumlah record rusak saat ini
                $rusak->jumlah -= 1;
                $rusak->save();

                // Buat record history baru untuk 1 barang yang diperbaiki
                BarangRusak::create([
                    'barang_id'  => $rusak->barang_id,
                    'detail_id'  => $rusak->detail_id,
                    'jumlah'     => 1,
                    'keterangan' => $rusak->keterangan,
                    'gambar'     => $rusak->gambar,
                    'status'     => 'diperbaiki'
                ]);
            } else {
                // Jika sisa 1, langsung ubah statusnya
                $rusak->status = 'diperbaiki';
                $rusak->save();
            }
        });

        return back()->with('success', '1 Barang berhasil diperbaiki dan stok telah bertambah.');
    }

    // buang barang 1 per 1
    public function dibuang($id)
    {
        $rusak = BarangRusak::findOrFail($id);

        if ($rusak->status !== 'rusak') {
            return back()->with('error', 'Barang sudah diproses');
        }

        DB::transaction(function () use ($rusak) {
           

            // PISAHKAN RECORD JIKA JUMLAH > 1
            if ($rusak->jumlah > 1) {
                // Kurangi jumlah record rusak saat ini
                $rusak->jumlah -= 1;
                $rusak->save();

                // Buat record history baru untuk 1 barang yang dibuang
                BarangRusak::create([
                    'barang_id'  => $rusak->barang_id,
                    'detail_id'  => $rusak->detail_id,
                    'jumlah'     => 1,
                    'keterangan' => $rusak->keterangan,
                    'gambar'     => $rusak->gambar,
                    'status'     => 'dibuang'
                ]);
            } else {
                // Jika sisa 1, langsung ubah statusnya
                $rusak->status = 'dibuang';
                $rusak->save();
            }
        });

        return back()->with('success', '1 Barang rusak berhasil dibuang.');
    }
}
