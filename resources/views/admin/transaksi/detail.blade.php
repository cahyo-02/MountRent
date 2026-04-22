@extends('layouts.admin')

@section('content')
    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-sm border border-gray-100">

        {{-- HEADER --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-6 border-b border-gray-100 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                    Detail Transaksi
                    <span class="text-[#2D6A4F]">#{{ $transaksi->id }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan pantau status penyewaan pelanggan.</p>
            </div>
            <a href="{{ route('admin.transaksi.index') }}"
                class="px-5 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold transition-colors border border-gray-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        {{-- GRID INFORMASI USER & SEWA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

            {{-- Card Informasi User --}}
            <div
                class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300 flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Informasi Pelanggan</h2>
                </div>

                <div class="space-y-4 flex-grow">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                        <p class="text-base font-semibold text-gray-800">{{ $transaksi->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat Email</p>
                        <p class="text-base font-semibold text-gray-800">{{ $transaksi->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor WhatsApp/HP</p>
                        <p class="text-base font-semibold text-gray-800">{{ $transaksi->user->no_tlp }}</p>
                    </div>
                </div>

                {{-- Bagian Foto KTP --}}
                <div class="mt-5 pt-5 border-t border-gray-200/60">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Foto KTP Identitas</p>

                    {{-- Karena foto KTP ada di tabel transaksi, langsung panggil dari $transaksi --}}
                    @if ($transaksi->foto_ktp)
                        <a href="{{ asset('storage/' . $transaksi->foto_ktp) }}" target="_blank"
                            class="inline-block p-1.5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md hover:border-blue-300 transition-all group">
                            <img src="{{ asset('storage/' . $transaksi->foto_ktp) }}" alt="KTP {{ $transaksi->user->name }}"
                                class="h-20 md:h-24 w-auto object-cover rounded-lg group-hover:opacity-90 transition-opacity">
                        </a>
                        <p class="text-[10px] text-gray-400 mt-1.5">*Klik gambar untuk melihat ukuran penuh</p>
                    @else
                        <div
                            class="flex items-center gap-2 px-3 py-2 bg-gray-100/80 rounded-lg w-fit text-sm text-gray-500 border border-gray-200 border-dashed">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Foto KTP tidak tersedia
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Informasi Sewa --}}
            <div
                class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-[#2D6A4F]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Informasi Sewa</h2>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tgl Sewa</p>
                            <p class="text-base font-semibold text-gray-800">{{ $transaksi->tanggal_sewa->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tgl Kembali</p>
                            <p class="text-base font-semibold text-gray-800">
                                {{ $transaksi->tanggal_kembali->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="pt-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Transaksi</p>
                        <span
                            class="inline-block px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-widest border
                            @if ($transaksi->status == 'dibatalkan') bg-red-50 text-red-600 border-red-200
                            @elseif($transaksi->status == 'dikembalikan') bg-green-50 text-green-600 border-green-200
                            @elseif($transaksi->status == 'dipinjam') bg-indigo-50 text-indigo-600 border-indigo-200
                            @elseif($transaksi->status == 'menunggu_verifikasi') bg-yellow-50 text-yellow-600 border-yellow-200 {{-- Tambahkan baris ini --}}
                            @elseif($transaksi->status == 'dibayar') bg-green-50 text-green-600 border-green-200 {{-- Tambahkan baris ini --}}
                            @else bg-blue-50 text-blue-600 border-blue-200 @endif">
                            {{ str_replace('_', ' ', $transaksi->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- INFORMASI PEMBAYARAN --}}
        @if ($transaksi->pembayaran)
            <div class="bg-[#1B4332]/5 p-6 md:p-8 rounded-2xl border border-[#1B4332]/10 mb-10">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Status Pembayaran</h2>
                        <div class="flex items-center gap-4">
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Metode</p>
                                <p class="font-bold text-[#1B4332] uppercase">{{ $transaksi->pembayaran->metode }}</p>
                            </div>
                            <div class="w-px h-10 bg-gray-300"></div>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Status</p>
                                @if ($transaksi->pembayaran->status == 'lunas')
                                    <span
                                        class="px-3 py-1 bg-green-500 text-white rounded text-sm font-semibold shadow-sm">Lunas</span>
                                @elseif ($transaksi->pembayaran->status == 'gagal' || $transaksi->status == 'dibatalkan')
                                    <span
                                        class="px-3 py-1 bg-red-500 text-white rounded text-sm font-semibold shadow-sm">Batal
                                        / Gagal</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded text-sm font-semibold shadow-sm">Menunggu
                                        Verifikasi</span>
                                @endif
                            </div>
                        </div>

                        @if (
                            $transaksi->pembayaran->status != 'lunas' &&
                                $transaksi->pembayaran->status != 'gagal' &&
                                $transaksi->status != 'dibatalkan')
                            <p
                                class="text-sm text-gray-600 mt-4 bg-white/60 p-3 rounded-xl inline-block border border-gray-200">
                                @if (
                                    $transaksi->pembayaran->status != 'lunas' &&
                                        $transaksi->pembayaran->status != 'gagal' &&
                                        $transaksi->status != 'dibatalkan')
                                    <p
                                        class="text-sm text-gray-600 mt-4 bg-white/60 p-3 rounded-xl inline-block border border-gray-200">
                                        @if ($transaksi->pembayaran->metode == 'transfer')
                                            ℹ️ Silakan cek bukti pembayaran user di bawah ini sebelum memverifikasi.
                                        @elseif ($transaksi->pembayaran->metode == 'cod')
                                            ℹ️ User akan membayar di tempat. Klik tombol setelah menerima uang.
                                        @else
                                            {{-- Tambahkan untuk Midtrans/Otomatis --}}
                                            ℹ️ User telah melakukan pembayaran via Midtrans. Silakan verifikasi untuk
                                            memproses pesanan.
                                        @endif
                                    </p>
                                @endif
                            </p>
                        @endif
                    </div>

                    @if (
                        $transaksi->status != 'dibatalkan' &&
                            $transaksi->pembayaran->status != 'lunas' &&
                            $transaksi->pembayaran->status != 'gagal')
                        <form action="{{ route('admin.pembayaran.verifikasi', $transaksi->pembayaran->id) }}"
                            method="POST" class="shrink-0">
                            @csrf
                            <button
                                class="bg-[#2D6A4F] hover:bg-[#1B4332] text-white px-6 py-3 rounded-xl font-bold shadow-md transition-all duration-300 hover:-translate-y-0.5">
                                @if ($transaksi->pembayaran->metode == 'cod')
                                    Konfirmasi Pembayaran Diterima
                                @else
                                    Verifikasi Pembayaran Sekarang
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{-- BUKTI PEMBAYARAN --}}
        @if ($transaksi->pembayaran && $transaksi->pembayaran->bukti)
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Bukti Pembayaran Transfer</h2>
                <div class="inline-block p-2 bg-gray-50 border border-gray-200 rounded-2xl shadow-sm">
                    <img src="{{ asset('storage/' . $transaksi->pembayaran->bukti) }}" alt="Bukti Pembayaran"
                        class="w-64 max-w-full rounded-xl object-cover hover:opacity-90 transition-opacity cursor-pointer">
                </div>
            </div>
        @endif

        {{-- TABEL BARANG DISEWA --}}
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Barang Disewa</h2>
        <div class="overflow-x-auto mb-10 bg-white border border-gray-100 rounded-2xl shadow-sm">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Barang</th>
                        <th class="px-6 py-4 text-center font-semibold">Jumlah</th>
                        <th class="px-6 py-4 text-right font-semibold">Harga / Hari</th>
                        <th class="px-6 py-4 text-right font-semibold">Subtotal</th>
                        <th class="px-6 py-4 text-center font-semibold">Kondisi Saat Kembali</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($transaksi->details as $detail)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $detail->barang->nama_barang }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $detail->jumlah }}</td>
                            <td class="px-6 py-4 text-right text-gray-600">Rp
                                {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp
                                {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($transaksi->status == 'dikembalikan' && $detail->jumlah > 0)
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <form action="{{ route('admin.transaksi.pengembalian') }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <input type="hidden" name="detail_id" value="{{ $detail->id }}">
                                            <input type="hidden" name="kondisi" value="aman">
                                            <button
                                                class="bg-green-100 hover:bg-green-200 text-green-700 font-semibold text-xs px-3 py-1.5 rounded-lg border border-green-200 transition-colors">Aman</button>
                                        </form>

                                        <form action="{{ route('admin.maintenance.store') }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <input type="hidden" name="detail_id" value="{{ $detail->id }}">
                                            <input type="hidden" name="barang_id" value="{{ $detail->barang->id }}">
                                            <input type="hidden" name="kondisi" value="basah">
                                            <button
                                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold text-xs px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">Basah</button>
                                        </form>

                                        <form action="{{ route('admin.maintenance.store') }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <input type="hidden" name="detail_id" value="{{ $detail->id }}">
                                            <input type="hidden" name="barang_id" value="{{ $detail->barang->id }}">
                                            <input type="hidden" name="kondisi" value="kotor">
                                            <button
                                                class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 font-semibold text-xs px-3 py-1.5 rounded-lg border border-yellow-200 transition-colors">Kotor</button>
                                        </form>

                                        <button onclick="openRusakModal({{ $detail->id }})"
                                            class="bg-red-100 hover:bg-red-200 text-red-700 font-semibold text-xs px-3 py-1.5 rounded-lg border border-red-200 transition-colors">Rusak</button>
                                        <button onclick="openHilangModal({{ $detail->id }})"
                                            class="bg-gray-800 hover:bg-black text-white font-semibold text-xs px-3 py-1.5 rounded-lg transition-colors">Hilang</button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- UPDATE STATUS TRANSAKSI SECTION --}}
        <div class="mt-8 pt-8 border-t border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ubah Status Transaksi</h3>

            @if ($transaksi->status == 'dibatalkan')
                <div class="bg-red-50 border border-red-200 p-5 rounded-2xl flex items-start md:items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-red-800 text-lg">Transaksi Telah Dibatalkan</p>
                        <p class="text-sm text-red-600 mt-1">Status tidak dapat diubah lagi dan stok barang telah
                            dikembalikan ke sistem.</p>
                    </div>
                </div>
            @else
                <form action="{{ route('admin.transaksi.updateStatus', $transaksi->id) }}" method="POST"
                    class="bg-gray-50 p-6 rounded-2xl border border-gray-100 inline-block w-full md:w-auto">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <label for="status" class="text-sm font-bold text-gray-600 uppercase tracking-wider">Pilih
                            Status:</label>
                        <select name="status" id="status"
                            class="block w-full md:w-64 border-gray-200 rounded-xl px-4 py-2.5 bg-white focus:ring-[#2D6A4F] focus:border-[#2D6A4F] transition-shadow shadow-sm">
                            <option value="dibatalkan" {{ $transaksi->status == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan</option>
                            <option value="dipinjam" {{ $transaksi->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                            </option>
                            <option value="dikembalikan" {{ $transaksi->status == 'dikembalikan' ? 'selected' : '' }}>
                                Dikembalikan</option>
                        </select>
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-sm transition-colors w-full md:w-auto">
                            Update Status
                        </button>
                    </div>
                </form>
            @endif
        </div>

        {{-- MODAL RUSAK --}}
        <div id="rusakModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all">
            <div class="bg-white p-8 rounded-3xl w-[90%] md:w-[450px] shadow-2xl">
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-5">
                    <h3 class="text-xl font-bold text-gray-800">Lapor Barang Rusak</h3>
                    <button onclick="closeRusakModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.barang_rusak.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="detail_id" id="rusak_detail_id" value="{{ old('detail_id') }}">
                    <input type="hidden" name="jumlah" value="1">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Kerusakan</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                            placeholder="Contoh: Frame tenda patah"
                            class="w-full border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-[#2D6A4F] focus:border-[#2D6A4F] @error('keterangan') border-red-500 @enderror"
                            required>
                        @error('keterangan')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Foto Bukti <span
                                class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                        <input type="file" name="gambar[]" multiple accept="image/*"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 bg-gray-50 focus:bg-white focus:ring-[#2D6A4F] focus:border-[#2D6A4F] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('gambar.*')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Denda (Rp) <span
                                class="text-gray-400 font-normal text-xs">(Kosongkan jika tidak ada)</span></label>
                        <input type="number" name="denda" value="{{ old('denda') }}" min="0"
                            placeholder="0"
                            class="w-full border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-[#2D6A4F] focus:border-[#2D6A4F] @error('denda') border-red-500 @enderror">
                        @error('denda')
                            <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeRusakModal()"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold shadow-md transition-colors">Simpan
                            Laporan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL HILANG --}}
        <div id="hilangModal"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden items-center justify-center z-50 transition-all">
            <div class="bg-white p-8 rounded-3xl w-[90%] md:w-[450px] shadow-2xl">
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-5">
                    <h3 class="text-xl font-bold text-gray-800">Lapor Barang Hilang</h3>
                    <button onclick="closeHilangModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('admin.denda.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="detail_id" id="hilang_detail_id">
                    <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                    <input type="hidden" name="jenis" value="hilang">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Kehilangan</label>
                        <input type="text" name="keterangan"
                            class="w-full border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-gray-900 focus:border-gray-900"
                            placeholder="Misal: Hilang di jalur pendakian" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Denda/Ganti Rugi (Rp)</label>
                        <input type="number" name="jumlah"
                            class="w-full border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white focus:ring-gray-900 focus:border-gray-900"
                            placeholder="Harga barang" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeHilangModal()"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors">Batal</button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl font-bold shadow-md transition-colors">Simpan
                            & Denda</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openRusakModal(detailId) {
                document.getElementById('rusak_detail_id').value = detailId;
                const modal = document.getElementById('rusakModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeRusakModal() {
                const modal = document.getElementById('rusakModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function openHilangModal(detailId) {
                document.getElementById('hilang_detail_id').value = detailId;
                const modal = document.getElementById('hilangModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeHilangModal() {
                const modal = document.getElementById('hilangModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.addEventListener('keydown', function(event) {
                if (event.key === "Escape") {
                    closeRusakModal();
                    closeHilangModal();
                }
            });
        </script>

    </div>
@endsection
