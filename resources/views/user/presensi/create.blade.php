@extends('layouts.appuser')

@section('content')
<div class="max-w-2xl mx-auto px-4 pt-6 pb-24 space-y-8">

    <!-- Judul -->
    <div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent mb-2">
            Presensi Hari Ini
        </h2>
        <p class="text-sm text-gray-600">Silakan selfie dan pastikan lokasi aktif sebelum absen.</p>

        <!-- Tombol Riwayat Presensi -->
        <div class="mt-4">
            <a href="{{ route('presensi.riwayat') }}"
               class="inline-block bg-gradient-to-r from-[#023392] to-[#266EAF] text-white px-4 py-2 rounded shadow hover:from-[#012e83] hover:to-[#215c9a] text-sm transition duration-200">
                📄 Lihat Riwayat Presensi
            </a>
        </div>
    </div>

    <!-- Notifikasi -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 rounded-lg p-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 rounded-lg p-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Presensi -->
    <form method="POST" action="{{ route('presensi.store') }}" class="space-y-6">
        @csrf

        <!-- FOTO SELFIE -->
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Ambil Foto Selfie</label>
            <div class="flex flex-col items-center space-y-2">
                <video id="video" width="320" height="240" autoplay class="rounded border shadow mirror"></video>
                <canvas id="canvas" width="320" height="240" class="hidden"></canvas>
                <input type="hidden" name="foto_selfie" id="foto_selfie">
                <button type="button" onclick="ambilFoto()"
                    class="mt-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Ambil Foto
                </button>
            </div>
        </div>

        <!-- KOORDINAT -->
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Koordinat Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="w-full px-4 py-2 bg-gray-100 border rounded" readonly>
            <input type="hidden" name="alamat_lengkap" id="alamat_lengkap">
            <input type="hidden" name="akurasi" id="akurasi">
            <p id="accuracy_info" class="text-sm text-gray-500 mt-1"></p>
        </div>

        <!-- PETA -->
        <div>
            <label class="block font-semibold text-gray-700 mb-2">Lokasi di Peta</label>
            <div class="relative z-0">
                <div id="map" class="w-full h-64 rounded-lg border shadow-sm z-0"></div>
            </div>
        </div>

        <!-- TOMBOL SUBMIT -->
        <div>
            <button type="submit"
                class="w-full py-3 px-6 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow text-lg font-semibold">
                Absen Sekarang
            </button>
        </div>
    </form>
</div>

<!-- STYLE -->
<style>
    .mirror {
        transform: scaleX(-1);
    }
    .leaflet-container {
        z-index: 0 !important;
    }
</style>

<!-- LEAFLET -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<!-- SCRIPT -->
<script>
    // Kamera selfie
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            document.getElementById('video').srcObject = stream;
        })
        .catch(() => {
            alert('Kamera tidak bisa diakses.');
        });

    // Ambil Foto
    function ambilFoto() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        canvas.classList.remove('hidden');
        ctx.save();
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        ctx.restore();

        const imageData = canvas.toDataURL('image/jpeg');
        document.getElementById('foto_selfie').value = imageData;
    }

    // Geolokasi & Map
    navigator.geolocation.getCurrentPosition(async position => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        const acc = position.coords.accuracy;

        document.getElementById('lokasi').value = `${lat}, ${lon}`;
        document.getElementById('akurasi').value = acc;
        document.getElementById('accuracy_info').innerText = `Akurasi lokasi: ±${Math.round(acc)} meter`;

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
            const data = await response.json();
            document.getElementById('alamat_lengkap').value = data.display_name;
        } catch (e) {
            console.warn('Gagal mendapatkan alamat:', e);
        }

        const map = L.map('map').setView([lat, lon], 17);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([lat, lon]).addTo(map)
            .bindPopup("Lokasi Anda")
            .openPopup();
    }, error => {
        alert('Gagal mendapatkan lokasi. Aktifkan GPS Anda.');
    }, {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    });
</script>


<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Oke!',
            confirmButtonColor: '#10b981', // green
            background: '#f0fdf4'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonText: 'Coba Lagi',
            confirmButtonColor: '#ef4444', // red
            background: '#fef2f2'
        });
    @endif
</script>
@endsection
