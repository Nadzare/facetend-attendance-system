@extends('layouts.appuser')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow space-y-6">

    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent mb-1">
            Ajukan Izin / Sakit
        </h2>
        <p class="text-sm text-gray-600">Silakan isi formulir di bawah ini dengan lengkap dan unggah bukti jika diperlukan.</p>
    </div>

    <!-- Form -->
    <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Jenis -->
        <div>
            <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Permohonan</label>
            <select name="jenis" id="jenis"
                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
            </select>
            @error('jenis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Tanggal -->
        <div>
            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal"
                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                required>
            @error('tanggal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Alasan -->
        <div>
            <label for="alasan" class="block text-sm font-medium text-gray-700 mb-1">Alasan</label>
            <textarea name="alasan" id="alasan" rows="3"
                class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 resize-none"
                placeholder="Jelaskan alasan pengajuan izin atau sakit..." required></textarea>
            @error('alasan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Bukti -->
        <div>
            <label for="bukti" class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti (PDF/JPG/PNG)</label>
            <input type="file" name="bukti" id="bukti"
                class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition"
                required>
            @error('bukti') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Tombol -->
        <div class="flex justify-end pt-2">
            <button type="submit"
                class="bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white px-6 py-2 rounded-lg shadow text-sm font-medium">
                Kirim Permohonan
            </button>
        </div>
    </form>
</div>
@endsection
