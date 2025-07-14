<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - FaceTend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-5xl w-full grid md:grid-cols-2 items-stretch min-h-[500px]">

        {{-- KIRI: Form Register --}}
        <div class="p-8 sm:p-10 bg-white order-2 md:order-1 h-full flex flex-col justify-center">
            <h2 class="text-xl font-bold text-center text-gray-800 mb-4">Daftar Akun FaceTend</h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="name" :value="'Nama Lengkap'" />
                    <x-text-input id="name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="'Email'" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="'Password'" />
                    <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="'Konfirmasi Password'" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div>
                    <x-primary-button class="w-full justify-center">
                        {{ __('Daftar') }}
                    </x-primary-button>
                </div>

                <div class="text-center text-sm mt-4">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk</a>
                </div>
            </form>
        </div>

        {{-- KANAN: Welcome --}}
        <div class="bg-gradient-to-b from-[#023392] to-[#266EAF] text-white p-10 flex flex-col items-center justify-center space-y-2 order-1 md:order-2 h-full">
            <a href="https://www.starindojaya.com/" target="_blank">
                <img src="{{ asset('starindo.png') }}" alt="Logo" class="w-70 h-24 object-contain mb-2">
            </a>
            <h2 class="text-2xl font-bold">Selamat Datang di FaceTend</h2>
            <p class="text-sm text-blue-100 text-center">Silakan lengkapi data diri untuk membuat akun baru.</p>
        </div>
    </div>

</body>
</html>
