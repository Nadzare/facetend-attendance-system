@extends('layouts.appuser')

@section('content')
<div class="max-w-2xl mx-auto p-6 space-y-6">
    <!-- Judul -->
    <div class="border-b pb-2">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent mb-2">Profil Pengguna</h2>
        <p class="text-sm text-gray-500">Lihat dan kelola informasi akun Anda.</p>
    </div>

    <!-- Alert -->
    @if(session('status'))
        <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded shadow-sm">
            {{ session('status') }}
        </div>
    @endif

    <!-- Card Info -->
    <div class="bg-white rounded-lg shadow p-5 space-y-4">
        <div class="flex items-center space-x-4">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white w-12 h-12 rounded-full flex items-center justify-center text-lg font-semibold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t">
            <a href="{{ route('user.profile.edit') }}"
               class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded shadow">
                <i class="fas fa-edit mr-2"></i> Edit Profil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex items-center justify-center w-full bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium py-2 rounded shadow">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
