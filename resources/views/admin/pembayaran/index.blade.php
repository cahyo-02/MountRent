@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <h1 class="text-2xl font-bold mb-8 text-gray-800">
            Data Pembayaran
        </h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-gray-700">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">

                    <tr>
                        <th class="px-4 py-4 text-left">ID</th>
                        <th class="px-4 py-4 text-left">User</th>
                        <th class="px-4 py-4 text-left">Jumlah</th>
                        <th class="px-4 py-4 text-left">Metode</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Keterangan</th>
                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse ($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-5 font-semibold">
                                {{ $pembayaran->id }}
                            </td>

                            <td class="px-4 py-5">

                                <div class="font-medium">
                                    {{ $pembayaran->transaksi->user->name ?? '-' }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $pembayaran->transaksi->user->email ?? '-' }}
                                </div>

                            </td>

                            <td class="px-4 py-5 font-semibold">
                                Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-5">
                                {{ strtoupper($pembayaran->metode) }}
                            </td>

                            <td class="px-4 py-5 text-center">

                                @if ($pembayaran->status == 'lunas')
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs">
                                        Lunas
                                    </span>
                                @elseif($pembayaran->status == 'menunggu_verifikasi')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs">
                                        Menunggu Verifikasi
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                                        Belum Bayar
                                    </span>
                                @endif

                            </td>

                            <td class="px-4 py-5 text-center">

                                @if ($pembayaran->status == 'lunas')
                                    <span class="text-green-600 font-semibold">
                                        ✔ Lunas
                                    </span>
                                @elseif($pembayaran->status == 'menunggu_verifikasi')
                                    <span class="text-yellow-600 font-semibold">
                                        Menunggu Verifikasi Admin
                                    </span>
                                @else
                                    <span class="text-gray-500">
                                        Belum Bayar
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">
                                Belum ada data pembayaran
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-8">
            {{ $pembayarans->links() }}
        </div>

    </div>
@endsection
