@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg max-w-4xl mx-auto">

        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">
                Detail Denda #{{ $denda->id }}
            </h1>
            <a href="{{ route('admin.denda.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
                &larr; Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            {{-- Informasi Denda --}}
            <div class="bg-red-50 p-6 rounded-xl border border-red-100">
                <h2 class="font-semibold text-red-800 mb-4 text-lg border-b border-red-200 pb-2">Informasi Denda</h2>
                <div class="space-y-3 text-sm">
                    <p><strong class="text-gray-700 block">Jenis Denda:</strong> <span
                            class="uppercase font-medium text-red-600">{{ $denda->jenis }}</span></p>
                    <p><strong class="text-gray-700 block">Keterangan:</strong> {{ $denda->keterangan ?? '-' }}</p>
                    <p><strong class="text-gray-700 block">Total Tagihan:</strong> <span
                            class="font-bold text-lg text-red-600">Rp
                            {{ number_format($denda->jumlah, 0, ',', '.') }}</span></p>
                    <p><strong class="text-gray-700 block">Status:</strong>
                        @if ($denda->status == 'belum_dibayar')
                            <span
                                class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs font-bold mt-1 inline-block">Belum
                                Dibayar</span>
                        @else
                            <span
                                class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs font-bold mt-1 inline-block">Lunas</span>
                        @endif
                    </p>
                    <p><strong class="text-gray-700 block">Tanggal Dibuat:</strong>
                        {{ $denda->created_at->format('d M Y H:i') }}</p>
                </div>

                {{-- Tombol Lunas (Muncul jika belum dibayar) --}}
                @if ($denda->status == 'belum_dibayar')
                    <div class="mt-6 pt-4 border-t border-red-200">
                        <form action="{{ route('admin.denda.lunas', $denda->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menandai denda ini sebagai LUNAS?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition shadow-sm">
                                Tandai Sudah Lunas
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Informasi Pelanggan & Transaksi --}}
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4 text-lg border-b border-gray-200 pb-2">Informasi Pelanggan
                    </h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong class="text-gray-800">Nama:</strong>
                            {{ $denda->transaksi->user->name ?? 'User Terhapus' }}</p>
                        <p><strong class="text-gray-800">Email:</strong> {{ $denda->transaksi->user->email ?? '-' }}</p>
                        <p><strong class="text-gray-800">No. HP:</strong> {{ $denda->transaksi->user->no_tlp ?? '-' }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <h2 class="font-semibold text-gray-800 mb-4 text-lg border-b border-gray-200 pb-2">Terkait Transaksi
                    </h2>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong class="text-gray-800">ID Transaksi:</strong> #{{ $denda->transaksi_id }}</p>
                        <p><strong class="text-gray-800">Tanggal Sewa:</strong>
                            {{ \Carbon\Carbon::parse($denda->transaksi->tanggal_sewa)->format('d M Y') }}</p>
                        <p><strong class="text-gray-800">Tanggal Kembali:</strong>
                            {{ \Carbon\Carbon::parse($denda->transaksi->tanggal_kembali)->format('d M Y') }}</p>

                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('admin.transaksi.show', $denda->transaksi_id) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
                                Lihat Detail Transaksi Penuh &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
