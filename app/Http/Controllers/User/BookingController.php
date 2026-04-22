<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    // katalog barang
    public function index(Request $request)
    {
        $query = Barang::with('category')
            ->where('status', 'tersedia')
            ->where('keterangan', 'aktif');

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $barangs = $query->paginate(8);
        $categories = \App\Models\Category::all();

        return view('user.barang.index', compact('barangs', 'categories'));
    }

    // detail barang
    public function show(Barang $barang)
    {
        if ($barang->keterangan !== 'aktif') {
            abort(404);
        }

        return view('user.barang.detail', compact('barang'));
    }

    // form checkout
    public function create(Barang $barang)
    {
        if ($barang->keterangan !== 'aktif' || $barang->stok_ditampilkan <= 0) {
            return redirect()->back()
                ->with('error', 'Barang sedang tidak tersedia untuk disewa.');
        }

        return view('user.booking.checkout', compact('barang'));
    }

    // simpan booking
    public function store(Request $request, Barang $barang)
    {
        if ($barang->keterangan !== 'aktif' || $barang->stok_ditampilkan <= 0) {
            return back()->with('error', 'Barang sedang tidak tersedia.');
        }

        $request->validate([
            'tanggal_sewa'       => 'required|date|after_or_equal:today',
            'tanggal_akhir_sewa' => 'required|date|after_or_equal:tanggal_sewa',
            'jumlah'             => 'required|integer|min:1|max:' . $barang->stok_ditampilkan,
            'metode'             => 'required',
            'foto_ktp'           => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $transaksi = DB::transaction(function () use ($request, $barang) {

            // Upload KTP
            $ktpPath = $request->file('foto_ktp')->store('ktp', 'public');

            // Hitung durasi sewa
            $start = Carbon::parse($request->tanggal_sewa);
            $end   = Carbon::parse($request->tanggal_akhir_sewa);

            // +1 agar 5-8 dihitung 4 hari
            $jumlahHari = $start->diffInDays($end) + 1;

            // Hitung subtotal
            $subtotal = $barang->harga_sewa * $request->jumlah * $jumlahHari;

            // Buat transaksi
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'tanggal_sewa' => $request->tanggal_sewa,
                'tanggal_kembali' => $request->tanggal_akhir_sewa,
                'status' => 'menunggu_pembayaran',
                'total_harga' => $subtotal,
                'foto_ktp' => $ktpPath
            ]);

            // Detail transaksi
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'barang_id'    => $barang->id,
                'jumlah'       => $request->jumlah,
                'harga'        => $barang->harga_sewa,
                'subtotal'     => $subtotal
            ]);

            // Simpan pembayaran
            Pembayaran::create([
                'transaksi_id' => $transaksi->id,
                'jumlah_bayar' => $subtotal,
                'metode'       => $request->metode,
                'status'       => 'pending'
            ]);

            // Kurangi stok barang
            $barang->stok -= $request->jumlah;
            $barang->save();

            return $transaksi;
        });

        return redirect()
            ->route('user.transaksi.show', $transaksi->id)
            ->with('success', 'Booking berhasil dibuat, silakan lanjutkan pembayaran.');
    }
}
