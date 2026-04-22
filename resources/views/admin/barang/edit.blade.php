@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <h1 class="text-2xl font-bold mb-6">Edit Barang</h1>

        <form action="{{ route('admin.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- Nama Barang --}}
            <div class="mb-4">
                <label class="block mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                    class="border p-2 w-full rounded">
            </div>

            {{-- Deskripsi --}}
            <div class="mb-4">
                <label class="block mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="border p-2 w-full rounded">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
            </div>

            {{-- Harga --}}
            <div class="mb-4">
                <label class="block mb-1">Harga Sewa</label>
                <input type="number" name="harga_sewa" value="{{ old('harga_sewa', $barang->harga_sewa) }}"
                    class="border p-2 w-full rounded">
            </div>

            {{-- Stok --}}
            <div class="mb-4">
                <label class="block mb-1">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $barang->stok) }}"
                    class="border p-2 w-full rounded">
            </div>

            {{-- stok cadangan --}}
            <div class="mb-4">
                <label class="block mb-1">Stok Cadangan</label>

                <input type="number" name="stok_cadangan" value="{{ old('stok_cadangan', $barang->stok_cadangan) }}"
                    class="border p-2 w-full rounded">
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="block mb-1">Kategori</label>
                <select name="category_id" class="border p-2 w-full rounded">
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $barang->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Gambar --}}
            <div class="mb-4">
                <label class="block mb-1">Ganti Gambar (Opsional)</label>
                <input type="file" name="gambar" class="border p-2 w-full rounded">
            </div>

            @if ($barang->gambar)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" class="w-32 rounded shadow">
                </div>
            @endif

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                Update
            </button>

        </form>

    </div>
@endsection
