@extends('layouts.appsuperadmin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-xl space-y-6">
    <!-- Heading -->
    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent">
            Tambah Admin Baru
        </h1>
        <p class="text-sm text-gray-600 mt-1">Lengkapi form berikut untuk menambahkan admin ke dalam sistem.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.admin.admin-management.store') }}" class="space-y-5">
        @csrf

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                   required>
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                   required>
            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                   required>
            @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3 pt-4">
            <a href="{{ route('superadmin.admin.admin-management.index') }}"
               class="text-gray-600 hover:underline text-sm text-center sm:text-left">← Kembali</a>
            <button type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg shadow">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
