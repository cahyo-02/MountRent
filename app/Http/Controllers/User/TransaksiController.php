<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.transaksi.index', compact('transaksis'));
    }

    public function show(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        $transaksi->load('details.barang', 'pembayaran', 'dendas');

        // --- TAMBAHAN KODE MIDTRANS MULAI DARI SINI ---
        $snapToken = null;
        if ($transaksi->status == 'menunggu_pembayaran') {
            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Kita buat order_id unik gabungan dari ID Transaksi dan Timestamp 
            // agar tidak error jika user buka-tutup popup pembayaran
            $orderId = 'TRX-' . $transaksi->id . '-' . time();

            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderId,
                    // Kita tambahkan (int) agar memastikan harganya adalah angka bulat
                    'gross_amount' => (int) $transaksi->total_harga,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
        }
        // --- BATAS TAMBAHAN KODE MIDTRANS ---

        return view('user.transaksi.show', compact('transaksi', 'snapToken'));
    }

    public function struk(Transaksi $transaksi)
    {
        $transaksi->load([
            'user',
            'details.barang',
            'pembayaran',
            'dendas'
        ]);

        return view('user.transaksi.struk', compact('transaksi'));
    }

    public function batal(Transaksi $transaksi)
    {
        // 1. Pastikan yang membatalkan adalah user yang memiliki transaksi tersebut
        if ($transaksi->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Pastikan status masih bisa dibatalkan (mencegah user iseng membatalkan saat barang sedang dipinjam)
        if (!in_array($transaksi->status, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
            return back()->with('error', 'Transaksi tidak dapat dibatalkan karena sudah diproses oleh Admin.');
        }

        // 3. Kembalikan stok barang yang tadinya di-booking! (Sangat Penting)
        foreach ($transaksi->details as $detail) {
            $barang = $detail->barang;
            $barang->stok += $detail->jumlah;
            $barang->save();
        }

        // 4. Ubah status transaksi dan pembayaran
        $transaksi->update(['status' => 'dibatalkan']);

        if ($transaksi->pembayaran) {
            // Ubah menjadi 'gagal' sesuai dengan enum di database
            $transaksi->pembayaran->update(['status' => 'gagal']);
        }

        return back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
    }
}
