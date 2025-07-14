@extends('layouts.appadmin')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Tambah Karyawan
        </h1>
        <p class="text-sm text-gray-600 mt-1">Lengkapi form berikut untuk menambahkan karyawan baru ke dalam sistem.</p>
    </div>

    <form action="{{ route('admin.karyawan.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- User -->
        <div>
            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih User</label>
            <select name="user_id" id="user_id" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih User --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            @error('user_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('nama') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input type="text" name="nik" id="nik"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('nik') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Jabatan -->
        <div>
            <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500"
                   required>
            @error('jabatan') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Kamera -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ambil Foto Wajah</label>

            <video id="video" width="320" height="240" autoplay
                   class="rounded shadow border border-gray-300 mb-2 transform -scale-x-100"></video>

            <canvas id="canvas" width="320" height="240" class="hidden"></canvas>

            <button type="button" id="takePhoto"
                    class="mt-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded-lg shadow text-sm">
                Ambil Foto
            </button>

            <input type="hidden" name="foto_base64" id="foto_base64">

            <img id="preview" class="mt-4 rounded shadow w-32 hidden border border-gray-300" />
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3 pt-4">
            <a href="{{ route('admin.karyawan.index') }}"
            class="text-gray-600 hover:underline text-sm text-center sm:text-left">Batal</a>
            <button type="submit"
                    class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white rounded-lg shadow">
                Simpan
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const takePhotoBtn = document.getElementById('takePhoto');
    const fotoInput = document.getElementById('foto_base64');
    const previewImg = document.getElementById('preview');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        }).catch(err => {
            alert("Gagal mengakses kamera: " + err.message);
        });

    takePhotoBtn.addEventListener('click', function () {
        const context = canvas.getContext('2d');
        context.save();
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.restore();

        const dataUrl = canvas.toDataURL('image/jpeg');
        fotoInput.value = dataUrl;
        previewImg.src = dataUrl;
        previewImg.classList.remove('hidden');
    });
</script>
@endpush
