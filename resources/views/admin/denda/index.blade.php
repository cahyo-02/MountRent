@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <h1 class="text-2xl font-bold mb-8 text-gray-800">
            Data Denda
        </h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">

                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-4 text-left">ID</th>
                        <th class="px-4 py-4 text-left">User</th>
                        <th class="px-4 py-4 text-left">Jenis</th>
                        <th class="px-4 py-4 text-left">Keterangan</th>
                        <th class="px-4 py-4 text-left">Jumlah</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($dendas as $denda)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-5 font-semibold">
                                {{ $denda->id }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $denda->transaksi->user->name ?? '-' }}
                            </td>

                            <td class="px-4 py-5">
                                {{ ucfirst($denda->jenis) }}
                            </td>

                            <td class="px-4 py-5">
                                {{ $denda->keterangan ?? '-' }}
                            </td>

                            <td class="px-4 py-5 font-semibold">
                                Rp {{ number_format($denda->jumlah, 0, ',', '.') }}
                            </td>

                            {{-- KOLOM STATUS --}}
                            <td class="px-4 py-5 text-center">
                                @if ($denda->status == 'belum_dibayar')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Belum Dibayar
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Lunas
                                    </span>
                                @endif
                            </td>

                            {{-- KOLOM AKSI --}}
                            <td class="px-4 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('admin.denda.show', $denda->id) }}"
                                        class="bg-gray-100 text-gray-700 hover:bg-gray-200 border px-3 py-1.5 rounded text-sm font-medium transition">
                                        Detail
                                    </a>

                                    {{-- Tombol Tandai Lunas --}}
                                    @if ($denda->status == 'belum_dibayar')
                                        <form action="{{ route('admin.denda.lunas', $denda->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm font-medium hover:bg-blue-700 transition"
                                                onclick="return confirm('Tandai denda ini sudah lunas?')">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400">
                                Belum ada data denda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-6">
            {{ $dendas->links() }}
        </div>

    </div>
@endsection
