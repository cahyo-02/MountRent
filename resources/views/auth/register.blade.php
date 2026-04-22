<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - MountRent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased min-h-screen flex">

    <div class="w-full lg:w-3/5 flex items-center justify-center p-8 sm:p-12 overflow-y-auto">
        <div class="w-full max-w-xl">

            <a href="/" class="flex items-center gap-2 mb-8 lg:mb-12">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                </svg>
                <h1 class="text-2xl font-extrabold tracking-widest text-gray-900 uppercase">MountRent</h1>
            </a>

            <div class="mb-10 text-left">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Buat Akun Baru</h2>
                <p class="text-gray-500">Lengkapi data diri Anda di bawah ini untuk memulai.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-600 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="Jhon Doe">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="contoh@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. HP / WhatsApp</label>
                        <input type="text" name="no_tlp" value="{{ old('no_tlp') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="08123456789">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="Jl. Gunung Raya No. 1">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kata Sandi</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ulangi Kata Sandi</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 mt-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all duration-300 hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>
            </form>

            <p class="mt-8 text-center text-gray-600 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="text-emerald-600 font-bold hover:text-emerald-700 hover:underline">Masuk disini</a>
            </p>
        </div>
    </div>

    <div class="hidden lg:flex w-2/5 relative bg-gray-900">
        <img src="https://images.unsplash.com/photo-1551632811-561f31648753?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
            alt="Hiking Friends" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 right-0 p-16 text-right w-full">
            <h2 class="text-3xl font-bold text-white leading-tight mb-2">Bersama Lebih Seru.</h2>
            <p class="text-gray-300 text-md ml-auto max-w-sm">Dapatkan semua perlengkapan untuk tim Anda dalam satu
                tempat dengan proses yang sangat mudah.</p>
        </div>
    </div>

</body>

</html>
