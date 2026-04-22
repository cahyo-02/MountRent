@extends('layouts.admin')
_
@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <h1 class="text-2xl font-bold mb-8 text-gray-800">
            Data Transaksi
        </h1>

        <div class="mb-6 flex items-center gap-3">

            <form method="GET" action="{{ route('admin.transaksi.index') }}" class="flex gap-2">

                <select name="status" class="border rounded-lg px-3 py-2 text-sm">

                    <option value="">Semua Status</option>

                    {{-- TAMBAHAN: FILTER CEPAT PERLU DIPROSES --}}
                    <option value="perlu_diproses" {{ request('status') == 'perlu_diproses' ? 'selected' : '' }}
                        class="font-bold text-red-600">
                         Perlu Diproses (Notif)
                    </option>

                    <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>
                        Menunggu Pembayaran
                    </option>

                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                        Diproses
                    </option>

                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>
                        Dipinjam
                    </option>

                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>
                        Dikembalikan
                    </option>

                    <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>
                        Dibatalkan
                    </option>

                </select>

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    Filter
                </button>

                <a href="{{ route('admin.transaksi.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                    Reset
                </a>

            </form>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-gray-700">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-4 text-left">ID</th>
                        <th class="px-4 py-4 text-left">User</th>
                        <th class="px-4 py-4 text-left">Tanggal Sewa</th>
                        <th class="px-4 py-4 text-left">Tanggal Kembali</th>
                        <th class="px-4 py-4 text-left">Total</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach ($transaksis as $transaksi)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-5 font-semibold relative">
                                <div class="flex items-center gap-2">
                                    {{ $transaksi->id }}

                                    {{-- TAMBAHAN: Indikator Notifikasi Titik Merah --}}
                                    @if (in_array($transaksi->status, ['menunggu_pembayaran', 'diproses']) ||
                                            ($transaksi->pembayaran && $transaksi->pembayaran->status == 'menunggu_verifikasi'))
                                        <span class="relative flex h-3 w-3" title="Perlu Tindakan">
                                            <span
                                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-5">
                                <div class="font-medium">
                                    {{ $transaksi->user->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $transaksi->user->email }}
                                </div>
                            </td>

                            <td class="px-4 py-5">
                                {{ optional($transaksi->tanggal_sewa)->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-5">
                                {{ optional($transaksi->tanggal_kembali)->format('d-m-Y') }}
                            </td>

                            <td class="px-4 py-5 font-semibold">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-5 text-center">

                                {{-- STATUS TRANSAKSI --}}
                                @if ($transaksi->status == 'menunggu_pembayaran')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($transaksi->status == 'diproses')
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">
                                        Diproses
                                    </span>
                                @elseif($transaksi->status == 'dipinjam')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                        Dipinjam
                                    </span>
                                @elseif($transaksi->status == 'dikembalikan')
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs">
                                        Dikembalikan
                                    </span>
                                @elseif($transaksi->status == 'dibatalkan')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                        Dibatalkan
                                    </span>
                                @endif

                                {{-- STATUS PEMBAYARAN --}}
                                @if ($transaksi->pembayaran)
                                    @if ($transaksi->pembayaran->status == 'lunas')
                                        <div class="mt-1">
                                            <span class="px-2 py-1 bg-green-500 text-white text-xs rounded">
                                                Lunas
                                            </span>
                                        </div>
                                    @elseif($transaksi->pembayaran->status == 'menunggu_verifikasi')
                                        <div class="mt-1">
                                            <span class="px-2 py-1 bg-yellow-500 text-white text-xs rounded">
                                                Menunggu Verifikasi
                                            </span>
                                        </div>
                                    @elseif($transaksi->pembayaran->status == 'pending')
                                        <div class="mt-1">
                                            <span class="px-2 py-1 bg-gray-500 text-white text-xs rounded">
                                                Belum Bayar
                                            </span>
                                        </div>
                                    @endif
                                @endif

                                @if ($transaksi->ada_denda_belum_lunas)
                                    <div class="mt-1">
                                        <span class="px-2 py-1 bg-red-500 text-white text-xs rounded">
                                            Ada Denda
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-5 text-center">
                                <a href="{{ route('admin.transaksi.show', $transaksi->id) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $transaksis->links() }}
        </div>

    </div>
@endsection
