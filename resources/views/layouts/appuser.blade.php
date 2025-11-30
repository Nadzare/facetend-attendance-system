<!DOCTYPE html>
<html lang="en" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTend - User</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex flex-col min-h-screen text-gray-800">

    {{-- ✅ Header Branding --}}
    <header class="relative bg-gradient-to-r from-[#023392] to-[#266EAF] text-white rounded-b-2xl shadow-md">
        <div class="container mx-auto px-4 py-6 text-center">
            <h1 class="text-2xl md:text-3xl font-bold tracking-wide">FaceTend</h1>
            <p class="text-sm md:text-base text-blue-100">Sistem Presensi Modern Berbasis GPS & Kamera</p>
        </div>
    </header>

    {{-- ✅ Konten Utama --}}
    <main class="flex-1 px-4 pt-6 pb-24">
        <div class="max-w-screen-md mx-auto">
            @yield('content')
        </div>
    </main>

    {{-- ✅ Bottom Navbar --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-gradient-to-r from-[#023392] to-[#266EAF] text-white border-t border-blue-300">
        <div class="max-w-screen-md mx-auto flex justify-around items-center text-xs sm:text-sm py-2 px-2">
            <a href="{{ route('user.dashboard') }}"
               class="flex flex-col items-center {{ request()->routeIs('user.dashboard') ? 'text-white' : 'text-blue-100 hover:text-white' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="mt-1">Beranda</span>
            </a>

            <a href="{{ route('presensi.create') }}"
               class="flex flex-col items-center {{ request()->routeIs('user.presensi.*') ? 'text-white' : 'text-blue-100 hover:text-white' }}">
                <i class="fas fa-camera text-lg"></i>
                <span class="mt-1">Presensi</span>
            </a>

            <a href="{{ route('izin.index') }}"
               class="flex flex-col items-center {{ request()->routeIs('user.izin.*') ? 'text-white' : 'text-blue-100 hover:text-white' }}">
                <i class="fas fa-file-medical text-lg"></i>
                <span class="mt-1">Izin</span>
            </a>

        <a href="{{ route('user.profile') }}"
             class="flex flex-col items-center {{ request()->routeIs('user.profile') ? 'text-white' : 'text-blue-100 hover:text-white' }}">
            <i class="fas fa-user-circle text-lg"></i>
            <span class="mt-1">Profil</span>
        </a>


 

        </div>
    </nav>

    @stack('scripts')
</body>
</html>
