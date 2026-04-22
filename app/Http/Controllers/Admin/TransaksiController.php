<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{

    public function index(Request $request)
    {
        $query = Transaksi::with(['user', 'pembayaran'])->latest();

        if ($request->status) {
            // Logika kustom untuk filter "Perlu Diproses"
            if ($request->status === 'perlu_diproses') {
                // UBAH DISINI: Tambahkan 'menunggu_verifikasi'
                $query->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi', 'diproses']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('admin.transaksi.index', compact('transaksis'));
    }



    public function show(Transaksi $transaksi)
    {

        $transaksi->load([
            'user',
            'details.barang',
            'pembayaran',
            'dendas'
        ]);

        return view('admin.transaksi.detail', compact('transaksi'));
    }



    public function updateStatus(Request $request, Transaksi $transaksi)
    {

        DB::beginTransaction();

        try {

            $statusLama = $transaksi->status;
            $statusBaru = $request->status;

            $transaksi->load('details.barang');


            // STATUS DIPINJAM
            if ($statusBaru === 'dipinjam' && $statusLama !== 'dipinjam') {

                foreach ($transaksi->details as $detail) {

                    $barang = $detail->barang;

                    if ($barang->stok_ditampilkan < $detail->jumlah) {

                        DB::rollBack();

                        return back()->with(
                            'error',
                            'Stok ' . $barang->nama_barang . ' tidak mencukupi.'
                        );
                    }

                    $barang->decrement('stok_ditampilkan', $detail->jumlah);

                    if ($barang->stok_ditampilkan <= 0) {
                        $barang->update([
                            'status' => 'tidak tersedia'
                        ]);
                    }
                }
            }


            // STATUS DIKEMBALIKAN
            if ($statusBaru === 'dikembalikan' && $statusLama !== 'dikembalikan') {

                $transaksi->update([
                    'tanggal_dikembalikan' => now()
                ]);
            }



            // STATUS DIBATALKAN
            if ($statusBaru === 'dibatalkan' && $statusLama === 'dipinjam') {

                foreach ($transaksi->details as $detail) {

                    $barang = $detail->barang;

                    $barang->increment('stok_ditampilkan', $detail->jumlah);

                    if ($barang->stok_ditampilkan > 0) {

                        $barang->update([
                            'status' => 'tersedia'
                        ]);
                    }
                }
            }


            $transaksi->update([
                'status' => $statusBaru
            ]);

            DB::commit();

            return back()->with('success', 'Status berhasil diperbarui');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan : ' . $e->getMessage());
        }
    }



    public function uploadBukti(Request $request, Transaksi $transaksi)
    {

        $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $path = $request->file('bukti')->store('bukti', 'public');

        $transaksi->pembayaran->update([
            'bukti' => $path,
            'status' => 'menunggu_verifikasi'
        ]);

        return back()->with('success', 'Bukti berhasil dikirim');
    }



    public function kembalikanSatu($detailId)
    {

        $detail = \App\Models\DetailTransaksi::findOrFail($detailId);

        if ($detail->jumlah <= 0) {
            return back()->with('error', 'Tidak ada barang yang bisa dikembalikan');
        }

        $barang = $detail->barang;

        $barang->stok += 1;
        $barang->save();

        $barang->updateStokDitampilkan();

        $detail->jumlah -= 1;
        $detail->save();

        return back()->with('success', '1 barang berhasil dikembalikan');
    }




    public function prosesPengembalian(Request $request)
    {

        $request->validate([
            'detail_id' => 'required|exists:detail_transaksis,id',
            'kondisi' => 'required'
        ]);


        $detail = \App\Models\DetailTransaksi::findOrFail($request->detail_id);

        $barang = $detail->barang;

        $transaksi = $detail->transaksi;


        if ($detail->jumlah <= 0) {
            return back()->with('error', 'Barang sudah habis diproses');
        }



        // SIMPAN TANGGAL DIKEMBALIKAN
        if (!$transaksi->tanggal_dikembalikan) {

            $transaksi->update([
                'tanggal_dikembalikan' => now()
            ]);
        }



        // CEK TERLAMBAT (HANYA SEKALI)
        if (now()->gt($transaksi->tanggal_kembali)) {

            $sudahAda = \App\Models\Denda::where('transaksi_id', $transaksi->id)
                ->where('jenis', 'terlambat')
                ->exists();

            if (!$sudahAda) {

                $hariTelat = now()->diffInDays($transaksi->tanggal_kembali);

                \App\Models\Denda::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis' => 'terlambat',
                    'keterangan' => 'Terlambat ' . $hariTelat . ' hari',
                    'jumlah' => $hariTelat * 50000,
                    'status' => 'belum_dibayar'
                ]);
            }
        }



        // KONDISI BARANG
        if ($request->kondisi == 'aman') {

            $barang->stok += 1;
            $barang->save();
            $barang->updateStokDitampilkan();
        } elseif ($request->kondisi == 'basah' || $request->kondisi == 'kotor') {

            \App\Models\Maintenance::create([
                'barang_id' => $barang->id,
                'kondisi' => $request->kondisi,
                'jumlah' => 1,
                'keterangan' => 'Dari transaksi'
            ]);
        } elseif ($request->kondisi == 'rusak') {

            \App\Models\BarangRusak::create([
                'barang_id' => $barang->id,
                'jumlah' => 1,
                'keterangan' => 'Rusak saat penyewaan',
                'status' => 'rusak'
            ]);

            \App\Models\Denda::create([
                'transaksi_id' => $transaksi->id,
                'jenis' => 'rusak',
                'keterangan' => 'Barang rusak',
                'jumlah' => 200000,
                'status' => 'belum_dibayar'
            ]);
        } elseif ($request->kondisi == 'hilang') {

            \App\Models\Denda::create([
                'transaksi_id' => $transaksi->id,
                'jenis' => 'hilang',
                'keterangan' => 'Barang hilang',
                'jumlah' => $barang->harga,
                'status' => 'belum_dibayar'
            ]);
        }



        // KURANGI JUMLAH BARANG
        $detail->jumlah -= 1;
        $detail->save();



        // AUTO SELESAI TRANSAKSI
        if ($transaksi->details()->sum('jumlah') == 0) {

            $transaksi->update([
                'status' => 'selesai'
            ]);
        }


        return back()->with('success', 'Pengembalian berhasil diproses');
    }
}
