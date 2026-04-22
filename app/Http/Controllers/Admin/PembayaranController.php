<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with('transaksi.user')
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }

    public function verifikasi(Pembayaran $pembayaran)
    {
        if ($pembayaran->status === 'lunas') {
            return back()->with('info', 'Pembayaran sudah lunas.');
        }

        // ubah status pembayaran
        $pembayaran->update([
            'status' => 'lunas'
        ]);

        $pembayaran->transaksi->update([
            'status' => 'dibayar'
        ]);

        // ubah status transaksi menjadi dibayar
        if ($pembayaran->transaksi) {

            $pembayaran->transaksi->update([
                'status' => 'dibayar'
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
