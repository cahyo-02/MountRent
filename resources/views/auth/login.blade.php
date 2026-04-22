<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MountRent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased h-screen flex overflow-hidden">

    <div class="hidden lg:flex w-1/2 relative bg-gray-900">
        <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
            alt="Mountain" class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-16 w-full">
            <a href="/" class="flex items-center gap-2 mb-8">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                </svg>
                <h1 class="text-3xl font-extrabold tracking-widest text-white uppercase">MountRent</h1>
            </a>
            <h2 class="text-4xl font-bold text-white leading-tight mb-4">Selamat Datang Kembali.</h2>
            <p class="text-gray-300 text-lg max-w-md">Lanjutkan petualangan Anda dengan menyewa peralatan kualitas
                terbaik kami.</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 overflow-y-auto">
        <div class="w-full max-w-md">

            <a href="/" class="flex lg:hidden items-center justify-center gap-2 mb-10">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path>
                </svg>
                <h1 class="text-2xl font-extrabold tracking-widest text-gray-900 uppercase">MountRent</h1>
            </a>

            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                <p class="text-gray-500">Masukkan email dan kata sandi Anda untuk melanjutkan.</p>
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

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 outline-none"
                        placeholder="contoh@email.com">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lupa sandi?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-5 py-3.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all duration-200 outline-none"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 transition duration-200">
                    <label for="remember_me" class="ml-3 text-sm text-gray-600 cursor-pointer">Ingat saya</label>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 transition-all duration-300 hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </form>

            <p class="mt-8 text-center text-gray-600 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    class="text-emerald-600 font-bold hover:text-emerald-700 hover:underline">Daftar disini</a>
            </p>
        </div>
    </div>

</body>

</html>
