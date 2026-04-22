<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{

    public function index(Request $request)
    {
        $query = Barang::with('category');

        // Pencarian berdasarkan Nama Barang
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan Kategori (Untuk mendukung fitur klik dari kategori tadi)
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $barangs = $query->latest()->paginate(10);
        $categories = Category::all(); // Dibutuhkan untuk dropdown filter

        return view('admin.barang.index', compact('barangs', 'categories'));
    }


    public function create()
    {
        $categories = Category::all();

        return view('admin.barang.create', compact('categories'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'harga_sewa' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'stok_cadangan' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('barang', 'public');
        }

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'harga_sewa' => $request->harga_sewa,
            'stok' => $request->stok,
            'stok_cadangan' => $request->stok_cadangan,
            'category_id' => $request->category_id,
            'gambar' => $gambar,
            'status' => 'tersedia',
            'keterangan' => 'aktif'
        ]);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }



    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $categories = Category::all();

        return view('admin.barang.edit', compact('barang', 'categories'));
    }



    public function update(Request $request, Barang $barang)
    {

        $request->validate([
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'harga_sewa' => 'required|numeric',
            'stok' => 'required|integer|min:0',
            'stok_cadangan' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only([
            'nama_barang',
            'deskripsi',
            'harga_sewa',
            'stok',
            'stok_cadangan',
            'category_id'
        ]);

        if ($request->hasFile('gambar')) {

            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('barang', 'public');
        }

        $barang->update($data);

        return redirect()
            ->route('admin.barang.index')
            ->with('success', 'Barang berhasil diperbarui');
    }



    public function destroy($id)
    {

        $barang = Barang::findOrFail($id);

        // CEK BARANG SEDANG DIPINJAM
        if ($barang->detailTransaksis()
            ->whereHas('transaksi', function ($q) {
                $q->where('status', 'dipinjam');
            })->exists()
        ) {

            return back()->with('error', 'Barang sedang dipinjam dan tidak bisa dinonaktifkan.');
        }


        // TOGGLE AKTIF / NONAKTIF
        if ($barang->keterangan == 'aktif') {

            $barang->update([
                'keterangan' => 'nonaktif'
            ]);

            return back()->with('success', 'Barang berhasil dinonaktifkan');
        }


        $barang->update([
            'keterangan' => 'aktif'
        ]);

        return back()->with('success', 'Barang berhasil diaktifkan kembali');
    }



    // selesai maintenance

    public function selesaiMaintenance($id)
    {

        $barang = Barang::findOrFail($id);

        $barang->increment('stok_ditampilkan', 1);

        if ($barang->stok_ditampilkan > 0) {

            $barang->update([
                'status' => 'tersedia',
                'keterangan' => 'aktif'
            ]);
        }

        return back()->with('success', 'Barang selesai maintenance dan kembali ke stok.');
    }
}
