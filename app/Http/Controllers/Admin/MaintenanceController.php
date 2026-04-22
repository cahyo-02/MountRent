<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\DetailTransaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('barang')
            ->latest()
            ->paginate(10);

        return view('admin.maintenance.index', compact('maintenances'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'detail_id' => 'required|exists:detail_transaksis,id',
            'barang_id' => 'required|exists:barangs,id',
            'kondisi' => 'required|string',
        ]);

        $detail = DetailTransaksi::findOrFail($request->detail_id);

        if ($detail->jumlah <= 0) {
            return back()->with('error', 'Barang sudah diproses atau jumlah tidak mencukupi');
        }

        $barang = Barang::findOrFail($request->barang_id);

        DB::transaction(function () use ($request, $detail, $barang) {
            // 1. Simpan data ke tabel maintenances
            Maintenance::create([
                'barang_id' => $barang->id,
                'kondisi' => $request->kondisi,
                'jumlah' => 1,
                'keterangan' => 'Masuk maintenance dari pengembalian transaksi #' . $detail->transaksi_id
            ]);

            // 2. Kurangi stok utama di gudang (PENTING!)
            $barang->stok -= 1;
            $barang->save();

            // 3. Update stok ditampilkan & status (agar sinkron di katalog user)
            if (method_exists($barang, 'updateStokDitampilkan')) {
                $barang->updateStokDitampilkan();
            }

            // 4. Kurangi jumlah di detail transaksi pengembalian
            $detail->jumlah -= 1;
            $detail->save();
        });

        return back()->with('success', 'Barang berhasil dipindahkan ke Maintenance dan stok gudang dikurangi');
    }

    public function selesai($id)
    {
        // BENAR: Cari data maintenance berdasarkan ID yang dikirim
        $maintenance = \App\Models\Maintenance::findOrFail($id);

        // Proses kembalikan stok barang
        $barang = \App\Models\Barang::findOrFail($maintenance->barang_id);
        $barang->stok += $maintenance->jumlah;
        $barang->save();

        // Hapus data maintenance atau ubah statusnya
        $maintenance->delete(); // atau $maintenance->update(['status' => 'selesai']);

        // Redirect
        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance selesai!');
    }
    public function show($id)
    {
        // Cari data maintenance dan relasi barangnya
        $maintenance = Maintenance::with('barang')->findOrFail($id);

        return view('admin.maintenance.show', compact('maintenance'));
    }
}
