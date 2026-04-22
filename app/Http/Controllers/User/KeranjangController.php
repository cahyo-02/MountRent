<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $keranjangs = Keranjang::with('barang')->where('user_id', Auth::id())->get();
        return view('user.keranjang.index', compact('keranjangs'));
    }

    // Menambah barang ke keranjang
    public function store(Request $request, $barang_id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($barang_id);

        // Cek apakah barang sudah ada di keranjang user ini
        $keranjang = Keranjang::where('user_id', Auth::id())
            ->where('barang_id', $barang_id)
            ->first();

        if ($keranjang) {
            // Jika sudah ada, tambahkan jumlahnya saja
            $keranjang->jumlah += $request->jumlah;
            $keranjang->save();
        } else {
            // Jika belum ada, buat data baru di keranjang
            Keranjang::create([
                'user_id' => Auth::id(),
                'barang_id' => $barang_id,
                'jumlah' => $request->jumlah
            ]);
        }

        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Menghapus barang dari keranjang
    public function destroy($id)
    {
        $keranjang = Keranjang::where('user_id', Auth::id())->findOrFail($id);
        $keranjang->delete();

        return redirect()->back()->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function checkoutPilihan(Request $request)
    {
        // Ambil ID keranjang yang dicentang user [cite: 31]
        $selectedIds = $request->input('selected_items');

        if (!$selectedIds) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu barang untuk di-booking.');
        }

        // Ambil data keranjang berdasarkan pilihan user [cite: 30]
        $items = Keranjang::with('barang')
            ->whereIn('id', $selectedIds)
            ->where('user_id', Auth::id())
            ->get();

        // Arahkan ke view checkout/booking dengan membawa data pilihan [cite: 30]
        return view('user.booking.checkout_multiple', compact('items'));
    }
}
