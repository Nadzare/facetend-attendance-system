<!DOCTYPE html>
<html
    lang="en"
    x-data="{ sidebarCollapsed: false }"
    class="h-full bg-gray-50"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FaceTend - Admin</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" defer></script>
</head>
<body class="h-full text-gray-800">

<div class="flex h-screen overflow-hidden">

        <div
        class="flex-1 flex flex-col transition-all duration-300"
        :class="sidebarCollapsed ? 'ml-20' : 'ml-64'"
    >
        <header class="bg-white shadow px-6 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-4">
                <button @click="sidebarCollapsed = !sidebarCollapsed" class="text-[#023392] focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <h2 class="text-xl font-semibold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent">
                    Admin Panel
                </h2>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

<aside
    :class="sidebarCollapsed ? 'w-20' : 'w-64'"
    class="fixed z-40 inset-y-0 left-0 bg-gradient-to-b from-[#023392] to-[#266EAF] text-white p-4 transition-all duration-300 ease-in-out flex flex-col justify-between"
>
    <div>
        <div class="flex items-center justify-center h-10 mb-8">
            <div class="flex items-center gap-2 font-bold text-xl" x-show="!sidebarCollapsed" x-transition.opacity>
                <i class="fas fa-user-shield text-white text-2xl"></i>
                <span>FaceTend</span>
            </div>
            <div x-show="sidebarCollapsed" x-transition.opacity>
                <i class="fas fa-user-shield text-white text-3xl"></i>
            </div>
        </div>

        <nav class="space-y-2 text-sm font-medium">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-[#1E4F89] {{ request()->routeIs('admin.dashboard') ? 'bg-[#1E4F89]' : '' }}">
                <i class="fas fa-home w-5 text-center text-lg"></i>
                <span x-show="!sidebarCollapsed">Dashboard</span>
            </a>
            <a href="{{ route('admin.karyawan.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-[#1E4F89] {{ request()->routeIs('karyawan.*') ? 'bg-[#1E4F89]' : '' }}">
                <i class="fas fa-users w-5 text-center text-lg"></i>
                <span x-show="!sidebarCollapsed">Karyawan</span>
            </a>
            <a href="{{ route('admin.presensi.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded hover:bg-[#1E4F89] {{ request()->routeIs('presensi.*') ? 'bg-[#1E4F89]' : '' }}">
                <i class="fas fa-calendar-check w-5 text-center text-lg"></i>
                <span x-show="!sidebarCollapsed">Presensi</span>
            </a>
                <a href="{{ route('admin.izin.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded hover:bg-[#1E4F89] {{ request()->routeIs('admin.izin.*') ? 'bg-[#1E4F89]' : '' }}">
                    <i class="fas fa-file-medical w-5 text-center text-lg"></i>
                    <span x-show="!sidebarCollapsed">Izin & Sakit</span>
                </a>
        </nav>
    </div>

    <div x-data="{ showDropdown: false }" class="relative" x-show="!sidebarCollapsed" x-transition.opacity>
        <div @click="showDropdown = !showDropdown" class="cursor-pointer flex items-center justify-between px-3 py-2 rounded hover:bg-[#1E4F89]">
            <span class="text-sm font-semibold flex items-center gap-2 truncate">
                <i class="fas fa-user-circle"></i> 
                <span>{{ Auth::user()->name }}</span>
            </span>
            <i class="fas fa-chevron-up text-xs transition-transform shrink-0" :class="{ 'rotate-180': showDropdown }"></i>
        </div>

        <div x-show="showDropdown" @click.outside="showDropdown = false"
             x-transition
             class="mt-2 bg-white text-gray-800 rounded shadow-md w-full absolute bottom-12 left-0 z-50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full px-4 py-2 text-sm hover:bg-gray-100 text-left flex items-center gap-2">
                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>


</div>

@stack('scripts')
</body>
</html>