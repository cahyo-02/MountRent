@extends('layouts.admin')

@section('content')
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard Admin
        </h1>
        <p class="text-gray-500 mt-1">
            Selamat datang, {{ auth()->user()->name }}
        </p>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

        <div class="bg-[#1B4332] text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total Transaksi</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalTransaksi ?? 0 }}</h3>
        </div>

        <div class="bg-[#2D6A4F] text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total User</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalUser ?? 0 }}</h3>
        </div>

        <div class="bg-[#40916C] text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total Barang</p>
            <h3 class="text-3xl font-bold mt-2">{{ $totalBarang ?? 0 }}</h3>
        </div>

        <div class="bg-emerald-600 text-white rounded-2xl p-6 shadow-lg">
            <p class="text-sm opacity-80">Total Pendapatan</p>
            <h3 class="text-3xl font-bold mt-2">
                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
            </h3>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Transaksi Terbaru
            </h3>

            @forelse($transaksiTerbaru ?? [] as $transaksi)
                <a href="{{ route('admin.transaksi.show', $transaksi->id) }}">

                    <div
                        class="flex justify-between py-3 border-b last:border-0 hover:bg-gray-50 cursor-pointer px-2 rounded">

                        <div>
                            <p class="font-medium">
                                {{ $transaksi->id }} - {{ $transaksi->user->name }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $transaksi->tanggal_sewa->format('d-m-Y') }}
                            </p>
                        </div>

                        <span class="font-semibold text-emerald-700">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </span>

                    </div>

                </a>

            @empty
                <p class="text-gray-400">Belum ada transaksi</p>
            @endforelse
        </div>

        <!-- Stok Rendah -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-700">
                Peringatan Stok Rendah
            </h3>

            @forelse($barangStokRendah ?? [] as $barang)
                <div class="flex justify-between py-3 border-b last:border-0">
                    <span>{{ $barang->nama_barang }}</span>
                    <span class="text-red-600 font-semibold">
                        {{ $barang->stok_ditampilkan }}
                    </span>
                </div>
            @empty
                <p class="text-gray-400">Semua stok aman</p>
            @endforelse
        </div>

    </div>
@endsection
