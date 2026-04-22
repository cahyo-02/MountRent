@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <h1 class="text-2xl font-bold mb-8 text-gray-800">
            Detail User
        </h1>

        {{-- INFO USER --}}
        <div class="grid md:grid-cols-2 gap-8 mb-10">

            <div class="space-y-3">
                <p><strong>Nama:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>No HP:</strong> {{ $user->no_tlp }}</p>
                <p><strong>Alamat:</strong> {{ $user->alamat }}</p>
                <p><strong>Total Transaksi:</strong> {{ $user->transaksis->count() }}</p>
                <p><strong>Total Sewa:</strong>
                    Rp {{ number_format($user->transaksis->sum('total_harga'), 0, ',', '.') }}
                </p>
            </div>

        </div>

        <h2 class="text-lg font-semibold mb-4">
            Riwayat Transaksi
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-4 text-left">ID</th>
                        <th class="px-4 py-4 text-left">Tanggal Sewa</th>
                        <th class="px-4 py-4 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-4 text-left">Status</th>
                        <th class="px-4 py-4 text-right">Total</th>
                        <th class="px-4 py-4 text-center">KTP</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($user->transaksis as $transaksi)
                        <tr>

                            <td class="px-4 py-5">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $transaksi->tanggal_sewa->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $transaksi->tanggal_kembali->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-5">
                                <span
                                    class="px-3 py-1 rounded-full text-xs
        @if ($transaksi->status == 'menunggu_pembayaran') bg-yellow-100 text-yellow-700
        @elseif($transaksi->status == 'diproses') bg-blue-100 text-blue-700
        @elseif($transaksi->status == 'dikembalikan') bg-green-100 text-green-700
        @elseif($transaksi->status == 'dibatalkan') bg-red-100 text-red-700
        @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                                </span>
                            </td>

                            <td class="px-4 py-5 text-right font-semibold">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-5 text-center">
                                @if ($transaksi->foto_ktp)
                                    <a href="{{ asset('storage/' . $transaksi->foto_ktp) }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        Lihat KTP
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-5 text-center text-gray-400">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
@endsection
