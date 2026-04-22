@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-8">

            <h1 class="text-2xl font-bold text-gray-800">
                Data Barang Rusak
            </h1>

        </div>


        <!-- ALERT SUCCESS -->
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif


        <!-- ALERT ERROR -->
        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif


        <!-- FORM TAMBAH BARANG RUSAK -->
        <div class="bg-gray-50 p-6 rounded-xl mb-10 border">

            <h3 class="font-semibold text-gray-700 mb-4">
                Tambah Barang Rusak
            </h3>

            <form action="{{ route('admin.barang_rusak.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-2">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-start">

                    <div class="flex flex-col">
                        <select name="barang_id" required
                            class="w-full border rounded-lg p-2 focus:ring-red-500 @error('barang_id') border-red-500 @enderror">
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                        @error('barang_id')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <input type="number" name="jumlah" required min="1" placeholder="Jumlah Rusak"
                            value="{{ old('jumlah') }}"
                            class="w-full border rounded-lg p-2 focus:ring-red-500 @error('jumlah') border-red-500 @enderror">
                        @error('jumlah')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <input type="file" name="gambar[]" multiple accept="image/*"
                            title="Bisa pilih lebih dari 1 foto kerusakan"
                            class="w-full border rounded-lg p-2 bg-white focus:ring-red-500 @error('gambar.*') border-red-500 @enderror">
                        <span class="text-[10px] text-gray-500 mt-1">*Opsional. Bisa pilih >1 foto.</span>
                        @error('gambar.*')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <input type="text" name="keterangan" placeholder="Keterangan" value="{{ old('keterangan') }}"
                            class="w-full border rounded-lg p-2 focus:ring-red-500 @error('keterangan') border-red-500 @enderror">
                        @error('keterangan')
                            <span class="text-xs text-red-600 mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col">
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg px-4 py-2 transition-colors">
                            Tambah
                        </button>
                    </div>

                </div>
            </form>

        </div>


        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Barang</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-left">Keterangan</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($barangRusaks as $rusak)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-4">
                                    @if ($rusak->barang && $rusak->barang->gambar)
                                        <img src="{{ asset('storage/' . $rusak->barang->gambar) }}"
                                            class="w-12 h-12 object-cover rounded-md border" alt="Foto Barang">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-md border flex items-center justify-center text-xs text-gray-400">
                                            -
                                        </div>
                                    @endif

                                    <span class="font-medium text-gray-800">
                                        {{ $rusak->barang->nama_barang ?? 'Barang Terhapus' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-center font-semibold">
                                {{ $rusak->jumlah }}
                            </td>

                            <td class="px-4 py-4 text-gray-600">
                                {{ $rusak->keterangan ?? '-' }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if ($rusak->status == 'rusak')
                                    <span
                                        class="px-3 py-1 text-[11px] uppercase tracking-wider font-semibold rounded-full bg-red-100 text-red-600">
                                        Rusak
                                    </span>
                                @elseif($rusak->status == 'diperbaiki')
                                    <span
                                        class="px-3 py-1 text-[11px] uppercase tracking-wider font-semibold rounded-full bg-green-100 text-green-600">
                                        Diperbaiki
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 text-[11px] uppercase tracking-wider font-semibold rounded-full bg-gray-200 text-gray-600">
                                        Dibuang
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center space-x-1 whitespace-nowrap">

                                <a href="{{ route('admin.barang_rusak.show', $rusak->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs inline-block shadow-sm transition-colors">
                                    Detail
                                </a>

                                @if ($rusak->status == 'rusak')
                                    <form action="{{ route('admin.barang_rusak.perbaiki', $rusak->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button onclick="return confirm('Apakah 1 barang ini sudah selesai diperbaiki?')"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs shadow-sm transition-colors">
                                            Perbaiki
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.barang_rusak.buang', $rusak->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button onclick="return confirm('Yakin ingin membuang 1 barang rusak ini?')"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded text-xs shadow-sm transition-colors">
                                            Buang
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400 bg-gray-50/50 italic">
                                Belum ada data barang rusak saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- PAGINATION -->
        <div class="mt-6">

            {{ $barangRusaks->links() }}

        </div>

    </div>
@endsection
