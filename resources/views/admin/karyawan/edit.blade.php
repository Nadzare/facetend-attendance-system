@extends('layouts.appadmin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Edit Karyawan
        </h1>
        <p class="text-sm text-gray-600 mt-1">Perbarui data karyawan berikut sesuai kebutuhan.</p>
    </div>

    <form action="{{ route('admin.karyawan.update', $karyawan) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $karyawan->nama) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('nama') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ old('nik', $karyawan->nik) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('nik') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Jabatan -->
        <div>
            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $karyawan->jabatan) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('jabatan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Tombol -->
        <div class="flex flex-col sm:flex-row justify-end sm:items-center gap-3 pt-4">
            <a href="{{ route('admin.karyawan.index') }}"
               class="text-gray-600 hover:underline text-sm sm:order-1 sm:mr-2">Batal</a>
            <button type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white rounded-lg shadow sm:order-2">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
