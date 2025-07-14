<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - FaceTend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-5xl w-full grid md:grid-cols-2 items-stretch min-h-[500px]">
        
        {{-- KIRI: Welcome --}}
        <div class="bg-gradient-to-b from-[#023392] to-[#266EAF] text-white p-10 flex flex-col items-center justify-center space-y-2 h-full">
            <a href="https://www.starindojaya.com/" target="_blank">
                <img src="{{ asset('starindo.png') }}" alt="Logo" class="w-70 h-24 object-contain mb-2">
            </a>
            <h2 class="text-2xl font-bold">Welcome to FaceTend</h2>
            <p class="text-sm text-blue-100 text-center">Sistem presensi modern berbasis selfie dan lokasi GPS.</p>
        </div>

        {{-- KANAN: Form Login --}}
        <div class="p-8 sm:p-10 bg-white h-full flex flex-col justify-center">
            <h2 class="text-xl font-bold text-center text-gray-800 mb-4">Masuk ke FaceTend</h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" :value="'Email'" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="'Password'" />
                    <x-text-input id="password" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center space-x-2">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div>
                    <x-primary-button class="w-full justify-center">
                        {{ __('Masuk') }}
                    </x-primary-button>
                </div>

                <div class="text-center text-sm mt-4">
                    Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Daftar</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
