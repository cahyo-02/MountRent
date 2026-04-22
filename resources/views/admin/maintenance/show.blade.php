@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.maintenance.index') }}"
                class="text-gray-600 hover:text-gray-800 flex items-center transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Maintenance
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

            <div class="bg-gray-50 p-8 border-b flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $maintenance->barang->nama_barang }}</h1>
                    <p class="text-gray-500 text-sm mt-1">ID Maintenance: #MNT-{{ $maintenance->id }}</p>
                </div>
                <div>
                    @if ($maintenance->kondisi == 'basah')
                        <span
                            class="px-5 py-2 text-sm font-bold uppercase tracking-wide rounded-full bg-blue-100 text-blue-600">
                            💧 Basah
                        </span>
                    @else
                        <span
                            class="px-5 py-2 text-sm font-bold uppercase tracking-wide rounded-full bg-yellow-100 text-yellow-600">
                            🧹 Kotor
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Foto Barang (Katalog
                            Utama)</h3>
                        @if ($maintenance->barang->gambar)
                            <img src="{{ asset('storage/' . $maintenance->barang->gambar) }}"
                                class="w-full h-64 object-cover rounded-xl border shadow-sm"
                                alt="Foto {{ $maintenance->barang->nama_barang }}">
                        @else
                            <div
                                class="w-full h-64 bg-gray-100 flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 text-gray-400">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm">Tidak ada foto katalog</span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Informasi Maintenance</h3>
                            <div class="mt-3 space-y-3 bg-white p-5 rounded-xl border shadow-sm">
                                <div class="flex justify-between border-b pb-3">
                                    <span class="text-gray-500">Jumlah Masuk</span>
                                    <span class="font-bold text-gray-800">{{ $maintenance->jumlah }} Unit</span>
                                </div>
                                <div class="flex justify-between border-b pb-3 pt-1">
                                    <span class="text-gray-500">Tanggal Masuk</span>
                                    <span
                                        class="font-medium text-gray-800">{{ $maintenance->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="flex justify-between pt-1">
                                    <span class="text-gray-500">Lama Proses</span>
                                    <span
                                        class="font-medium text-gray-800">{{ $maintenance->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Catatan / Sumber Laporan
                            </h3>
                            <p
                                class="mt-3 p-4 bg-gray-50 rounded-xl text-gray-700 italic border-l-4 border-indigo-500 shadow-inner">
                                "{{ $maintenance->keterangan ?? 'Masuk maintenance tanpa keterangan khusus.' }}"
                            </p>
                        </div>
                    </div>

                </div>

                <div class="mt-10 pt-6 border-t">
                    <form action="{{ route('admin.maintenance.selesai', $maintenance->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Tandai barang ini sudah selesai proses maintenance dan siap disewa kembali?')"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-green-100 flex justify-center items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Selesaikan Maintenance (Kembalikan ke Stok)
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
    