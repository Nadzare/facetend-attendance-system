<!DOCTYPE html>
<html lang="id" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FaceTend</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col text-gray-800">

    {{-- Header Branding --}}
    <header class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white rounded-b-[3rem] shadow-md">
        <div class="max-w-xl mx-auto px-6 py-8 text-center">
            <h1 class="text-3xl font-bold">FaceTend</h1>
            <p class="text-sm md:text-base text-blue-100">Sistem Presensi Berbasis GPS dan Kamera</p>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4">
        <div class="w-full max-w-md py-8">
            {{ $slot }}
        </div>
    </main>
</body>
</html>
