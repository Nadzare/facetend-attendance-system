@extends('layouts.appuser')

@section('content')
<div class="max-w-2xl mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Profil</h2>

    <form method="POST" action="{{ route('user.profile.update') }}" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium text-gray-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full mt-1 px-4 py-2 border rounded" required>
            @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full mt-1 px-4 py-2 border rounded" required>
            @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('user.profile') }}"
               class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded text-sm">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
