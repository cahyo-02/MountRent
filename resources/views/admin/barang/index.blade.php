@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">
                Data Barang
            </h1>

            <a href="{{ route('admin.barang.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl shadow transition">
                + Tambah Barang
            </a>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                <form action="{{ route('admin.barang.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-3 w-full md:w-auto">

                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama barang..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-64">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <select name="category_id" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 py-2">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>

                    @if (request('search') || request('category_id'))
                        <a href="{{ route('admin.barang.index') }}"
                            class="text-sm text-red-600 hover:underline flex items-center">
                            Bersihkan Filter
                        </a>
                    @endif
                </form>


            </div>
            <table class="min-w-full text-sm text-gray-700">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-4">ID</th>
                        <th class="px-4 py-4">Gambar</th>
                        <th class="px-4 py-4">Nama</th>
                        <th class="px-4 py-4">Kategori</th>
                        <th class="px-4 py-4">Deskripsi</th>
                        <th class="px-4 py-4">Harga</th>
                        <th class="px-4 py-4 text-center">Stok Gudang</th>
                        <th class="px-4 py-4 text-center">Cadangan</th>
                        <th class="px-4 py-4 text-center">Ditampilkan</th>
                        <th class="px-4 py-4 text-center">Dipakai</th>
                        <th class="px-4 py-4 text-center">Sisa Real</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Dibuat</th>
                        <th class="px-4 py-4 text-center">Update</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($barangs as $barang)
                        @php
                            $dipakai = $barang
                                ->detailTransaksis()
                                ->whereHas('transaksi', function ($q) {
                                    $q->whereIn('status', ['dipinjam', 'dibayar', 'menunggu_verifikasi']);
                                })
                                ->sum('jumlah');

                            $sisa = $barang->stok_ditampilkan - $dipakai;
                        @endphp

                        <tr class="hover:bg-gray-50 transition">

                            {{-- ID --}}
                            <td class="px-4 py-5 font-semibold">
                                {{ $barang->id }}
                            </td>

                            {{-- GAMBAR --}}
                            <td class="px-4 py-5">
                                @if ($barang->gambar)
                                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                                        class="w-20 h-20 object-cover rounded">
                                @else
                                    <span class="text-gray-500">Tidak ada gambar</span>
                                @endif
                            </td>

                            {{-- NAMA --}}
                            <td class="px-4 py-5 font-medium">
                                {{ $barang->nama_barang }}
                            </td>

                            {{-- KATEGORI --}}
                            <td class="px-4 py-5">
                                {{ $barang->category->nama ?? '-' }}
                            </td>

                            {{-- DESKRIPSI --}}
                            <td class="px-4 py-5 max-w-xs text-gray-600">
                                {{ Str::limit($barang->deskripsi, 60) }}
                            </td>

                            {{-- HARGA --}}
                            <td class="px-4 py-5 font-semibold whitespace-nowrap">
                                Rp {{ number_format($barang->harga_sewa, 0, ',', '.') }}
                            </td>

                            {{-- STOK --}}
                            <td class="px-4 py-5 text-center">
                                <span class="px-3 py-1 bg-gray-100 rounded text-xs">
                                    {{ $barang->stok }}
                                </span>
                            </td>

                            {{-- CADANGAN --}}
                            <td class="px-4 py-5 text-center">
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                    {{ $barang->stok_cadangan }}
                                </span>
                            </td>

                            {{-- DITAMPILKAN --}}
                            <td class="px-4 py-5 text-center">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                    {{ $barang->stok_ditampilkan }}
                                </span>
                            </td>

                            {{-- DIPAKAI --}}
                            <td class="px-4 py-5 text-center text-red-500 font-semibold">
                                {{ $dipakai }}
                            </td>

                            {{-- SISA REAL --}}
                            <td
                                class="px-4 py-5 text-center font-bold 
                            {{ $sisa <= 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $sisa }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-4 py-5 text-center">
                                @if ($barang->keterangan == 'aktif')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs">
                                        Aktif
                                    </span>
                                @elseif($barang->keterangan == 'nonaktif')
                                    <span class="px-3 py-1 bg-gray-200 text-gray-600 rounded-full text-xs">
                                        Nonaktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                        Maintenance
                                    </span>
                                @endif
                            </td>

                            {{-- CREATED --}}
                            <td class="px-4 py-5 text-center text-gray-500">
                                {{ $barang->created_at->format('d-m-Y') }}
                            </td>

                            {{-- UPDATED --}}
                            <td class="px-4 py-5 text-center text-gray-500">
                                {{ $barang->updated_at->format('d-m-Y') }}
                            </td>

                            {{-- AKSI --}}
                            <td class="px-4 py-5 text-center space-y-1">

                                <a href="{{ route('admin.barang.edit', $barang->id) }}"
                                    class="text-blue-600 hover:text-blue-800 block">
                                    Edit
                                </a>

                                <form action="{{ route('admin.barang.destroy', $barang->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    @if ($barang->keterangan == 'aktif')
                                        <button onclick="return confirm('Nonaktifkan barang ini?')"
                                            class="text-red-600 hover:text-red-800">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <button onclick="return confirm('Aktifkan kembali barang ini?')"
                                            class="text-green-600 hover:text-green-800">
                                            Aktifkan
                                        </button>
                                    @endif
                                </form>

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $barangs->links() }}
        </div>

    </div>
@endsection
