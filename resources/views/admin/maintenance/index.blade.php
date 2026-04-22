@extends('layouts.admin')

@section('content')
    <div class="bg-white p-10 rounded-2xl shadow-lg">

        <div class="flex justify-between items-center mb-8">

            <h1 class="text-2xl font-bold text-gray-800">
                Barang Maintenance
            </h1>

        </div>


        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif



        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Barang</th>
                        <th class="px-4 py-3 text-center">Kondisi</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($maintenances as $maintenance)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-4">
                                    @if ($maintenance->barang && $maintenance->barang->gambar)
                                        <img src="{{ asset('storage/' . $maintenance->barang->gambar) }}"
                                            class="w-12 h-12 object-cover rounded-md border" alt="Foto Barang">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-200 rounded-md border flex items-center justify-center text-xs text-gray-400">
                                            -
                                        </div>
                                    @endif

                                    <span class="font-medium text-gray-800">
                                        {{ $maintenance->barang->nama_barang ?? 'Barang Terhapus' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if ($maintenance->kondisi == 'basah')
                                    <span
                                        class="px-3 py-1 text-[11px] uppercase tracking-wider font-semibold rounded-full bg-blue-100 text-blue-600">
                                        Basah
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 text-[11px] uppercase tracking-wider font-semibold rounded-full bg-yellow-100 text-yellow-600">
                                        Kotor
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center font-semibold">
                                {{ $maintenance->jumlah }}
                            </td>

                            <td class="px-4 py-4 text-center space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.maintenance.show', $maintenance->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs inline-block shadow-sm transition-colors">
                                    Detail
                                </a>

                                <form action="{{ route('admin.maintenance.selesai', $maintenance->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button
                                        onclick="return confirm('Apakah proses maintenance (pembersihan/pengeringan) barang ini sudah selesai?')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs shadow-sm transition-colors">
                                        Selesai
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-400 bg-gray-50/50 italic">
                                Saat ini tidak ada barang yang masuk maintenance.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="mt-6">

            {{ $maintenances->links() }}

        </div>


    </div>
@endsection
