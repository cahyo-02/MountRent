@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.barang_rusak.index') }}" class="text-gray-600 hover:text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 p-8 border-b flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $barangRusak->barang->nama_barang }}</h1>
                    <p class="text-gray-500 text-sm mt-1">ID Transaksi: #BR-{{ $barangRusak->id }}</p>
                </div>
                <div>
                    @if ($barangRusak->status == 'rusak')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-600">Rusak</span>
                    @elseif($barangRusak->status == 'diperbaiki')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-600">Selesai
                            Diperbaiki</span>
                    @else
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-200 text-gray-600">Dibuang</span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Informasi Barang</h3>
                            <div class="mt-3 space-y-3">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-600">Jumlah Rusak</span>
                                    <span class="font-bold text-gray-800">{{ $barangRusak->jumlah }} Unit</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="text-gray-600">Tanggal Lapor</span>
                                    <span class="text-gray-800">{{ $barangRusak->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Keterangan / Alasan</h3>
                            <p class="mt-3 p-4 bg-gray-50 rounded-lg text-gray-700 italic border-l-4 border-red-500">
                                "{{ $barangRusak->keterangan ?? 'Tidak ada keterangan tambahan.' }}"
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Bukti Foto Kerusakan</h3>

                        @if ($barangRusak->gambar && count($barangRusak->gambar) > 0)
                            <div class="grid grid-cols-2 gap-3">

                                @foreach ($barangRusak->gambar as $foto)
                                    <a href="{{ asset('storage/' . $foto) }}" target="_blank" class="group relative">
                                        <img src="{{ asset('storage/' . $foto) }}"
                                            class="w-full h-32 object-cover rounded-xl border transition group-hover:opacity-75">
                                        <div
                                            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <span class="bg-black/50 text-white text-[10px] px-2 py-1 rounded">Lihat
                                                Besar</span>
                                        </div>
                                    </a>
                                @endforeach

                            </div>
                        @else
                            <div
                                class="bg-gray-100 rounded-xl h-40 flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <p class="text-xs">Tidak ada bukti foto</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($barangRusak->status == 'rusak')
                    <div class="mt-10 pt-6 border-t flex space-x-3">
                        <form action="{{ route('admin.barang_rusak.perbaiki', $barangRusak->id) }}" method="POST"
                            class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Tandai barang ini sudah selesai diperbaiki?')"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-green-100">
                                Selesai Diperbaiki
                            </button>
                        </form>

                        <form action="{{ route('admin.barang_rusak.buang', $barangRusak->id) }}" method="POST"
                            class="flex-1">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin membuang barang ini?')"
                                class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-xl transition">
                                Buang Barang
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
