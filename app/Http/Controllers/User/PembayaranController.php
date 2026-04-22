<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function upload(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = $request->file('bukti')->store('bukti', 'public');

        // update pembayaran
        $transaksi->pembayaran()->update([
            'bukti' => $path,
            'status' => 'menunggu_verifikasi'
        ]);

        // UPDATE STATUS TRANSAKSI
        $transaksi->update([
            'status' => 'menunggu_verifikasi'
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi admin.');
    }
}
