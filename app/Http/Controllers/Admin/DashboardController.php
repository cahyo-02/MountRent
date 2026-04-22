<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Denda;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransaksi = Transaksi::count();

        $totalUser = User::where('role', 'user')->count();

        $totalBarang = Barang::count();

        $transaksiDipinjam = Transaksi::where('status', 'dipinjam')->count();

        $totalDenda = Denda::where('status', 'lunas')->sum('jumlah');

        $totalPembayaran = Pembayaran::where('status', 'lunas')->sum('jumlah_bayar');

        $totalPendapatan = $totalPembayaran + $totalDenda;

        $transaksiTerbaru = Transaksi::with('user')
            ->latest()
            ->take(5)
            ->get();

        $barangStokRendah = Barang::where('stok_ditampilkan', '<=', 5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTransaksi',
            'totalUser',
            'totalBarang',
            'totalPendapatan',
            'transaksiDipinjam',
            'transaksiTerbaru',
            'barangStokRendah'
        ));
    }
}
