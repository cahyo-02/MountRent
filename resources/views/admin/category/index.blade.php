@extends('layouts.admin')

@section('content')
    <div class="flex flex-col md:flex-row gap-8">

        {{-- KOLOM KIRI: FORM TAMBAH KATEGORI --}}
        <div class="w-full md:w-1/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-8">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">Tambah Kategori</h3>
                    <p class="text-sm text-gray-500 mb-6">Buat kategori baru untuk mengelompokkan barang sewa.</p>

                    <form action="{{ route('admin.category.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                Kategori</label>
                            <input type="text" name="nama" id="nama"
                                class="block w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/20 transition-all duration-200 outline-none"
                                placeholder="Misal: Tenda, Carrier, Sepatu" required>
                            @error('nama')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="inline-flex items-center justify-center w-full px-4 py-3 bg-[#2D6A4F] hover:bg-[#1B4332] rounded-xl font-bold text-white shadow-sm hover:shadow-md transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TABEL DAFTAR KATEGORI --}}
        <div class="w-full md:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Kategori</h3>
                    <span
                        class="px-3 py-1 bg-green-50 text-[#2D6A4F] text-xs font-bold rounded-full border border-green-100">
                        Total: {{ $categories->total() }} Kategori
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-100">
                                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nama Kategori</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">
                                    Jumlah Jenis Barang</th>
                                <th
                                    class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-gray-800">{{ $category->nama }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <a href="{{ route('admin.barang.index', ['category_id' => $category->id]) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-full transition-colors border border-blue-100">
                                            {{ $category->barangs_count ?? 0 }} Barang
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua barang di dalamnya akan terpengaruh.')"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors tooltip"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-gray-200" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <span>Belum ada kategori ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
