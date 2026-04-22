<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalTransaksi = Transaksi::where('user_id', $userId)->count();

        $transaksiAktif = Transaksi::where('user_id', $userId)
            ->whereIn('status', ['dipinjam'])
            ->count();

        $transaksiSelesai = Transaksi::where('user_id', $userId)
            ->where('status', 'dikembalikan')
            ->count();

        $transaksiTerbaru = Transaksi::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalTransaksi',
            'transaksiAktif',
            'transaksiSelesai',
            'transaksiTerbaru'
        ));
    }
}
