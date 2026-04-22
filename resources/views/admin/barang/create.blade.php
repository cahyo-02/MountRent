@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow">

        <h1 class="text-2xl font-bold mb-6 text-gray-800">
            Tambah Barang
        </h1>

        <form action="{{ route('admin.barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Barang
                </label>
                <input type="text" name="nama_barang" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Kategori
                </label>
                <select name="category_id" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Deskripsi
                </label>
                <textarea name="deskripsi" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga Sewa
                    </label>
                    <input type="number" name="harga_sewa"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Stok
                    </label>
                    <input type="number" name="stok"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Stok Cadangan
                </label>

                <input type="number" name="stok_cadangan" value="0" class="w-full border rounded-lg p-3">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Gambar
                </label>
                <input type="file" name="gambar" class="w-full border rounded-lg p-3 bg-gray-50">
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow">
                    Simpan Barang
                </button>
            </div>

        </form>
    </div>
@endsection
