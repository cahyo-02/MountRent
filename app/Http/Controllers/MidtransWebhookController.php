<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Pembayaran;

class MidtransWebhookController extends Controller
{
    public function handler(Request $request)
    {
        // 1. Ambil server key dari .env
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // 2. Ambil data dari Midtrans
        $json = $request->getContent();
        $notification = json_decode($json);

        if (!$notification) {
            return response()->json(['message' => 'Invalid JSON'], 400);
        }

        // 3. Verifikasi Signature Key (Keamanan tambahan)
        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
        $signatureKey = $notification->signature_key;

        $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // 4. Proses Status Pembayaran
        $transactionStatus = $notification->transaction_status;

        // Ekstrak ID Transaksi dari Order ID (format kita tadi: TRX-{id}-timestamp)
        $idParts = explode('-', $orderId);
        $transaksiId = $idParts[1] ?? null;

        if (!$transaksiId) {
            return response()->json(['message' => 'Format Order ID tidak valid'], 400);
        }

        $transaksi = Transaksi::find($transaksiId);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // 5. Update Status Berdasarkan Respon Midtrans
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // PEMBAYARAN SUKSES
            $transaksi->update(['status' => 'dibayar']);

            // Cek apakah tabel pembayaran untuk transaksi ini sudah ada, kalau belum buat baru
            $pembayaran = Pembayaran::firstOrCreate(
                ['transaksi_id' => $transaksi->id],
                [
                    'jumlah_bayar' => $grossAmount,
                    'metode' => $notification->payment_type, // Menyimpan metode bayar (qris, bank_transfer, dll)
                    'status' => 'lunas'
                ]
            );

            // Jika pembayaran sudah ada, update saja statusnya
            $pembayaran->update([
                'status' => 'lunas',
                'metode' => $notification->payment_type
            ]);
        } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            // PEMBAYARAN GAGAL ATAU EXPIRED
            $transaksi->update(['status' => 'dibatalkan']);

            $pembayaran = Pembayaran::where('transaksi_id', $transaksi->id)->first();
            if ($pembayaran) {
                $pembayaran->update(['status' => 'gagal']);
            }

            // Opsional: Kembalikan stok barang
            foreach ($transaksi->details as $detail) {
                $barang = $detail->barang;
                $barang->increment('stok', $detail->jumlah);
            }
        } elseif ($transactionStatus == 'pending') {
            // PEMBAYARAN PENDING (Menunggu Pelanggan Transfer)
            $transaksi->update(['status' => 'menunggu_pembayaran']);
        }

        return response()->json(['message' => 'Webhook berhasil diproses']);
    }
}
