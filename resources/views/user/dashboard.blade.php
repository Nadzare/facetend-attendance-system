@extends('layouts.appuser') {{-- layout user dengan navbar bawah --}}

@section('content')
<div class="max-w-4xl mx-auto px-4 pt-6 pb-24 space-y-8"> {{-- padding bawah besar untuk navbar bawah --}}

    <!-- Heading -->
    <div class="mb-4">
        <h1 class="text-xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent">
            Dashboard
        </h1>        
        <p class="text-sm text-gray-600 mt-1">Halo {{ Auth::user()->name }}, ini ringkasan presensi kamu.</p>
    </div>

    <!-- Rekapan -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-xl p-4 shadow flex items-center justify-between">
            <div>
                <p class="text-sm">Hadir</p>
                <p class="text-2xl font-bold">{{ $rekap['hadir'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-xl p-4 shadow flex items-center justify-between">
            <div>
                <p class="text-sm">Izin / Sakit</p>
                <p class="text-2xl font-bold">{{ $rekap['izin'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-file-medical text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl p-4 shadow flex items-center justify-between">
            <div>
                <p class="text-sm">Alpha</p>
                <p class="text-2xl font-bold">{{ $rekap['alpha'] }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-times-circle text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="bg-white shadow rounded-2xl p-4">
        <h2 class="text-lg font-semibold text-[#023392]">Presensi Hari Ini</h2>
        @if($presensiHariIni)
            <p class="mt-2 text-green-700">
                ✅ Kamu sudah presensi jam {{ \Carbon\Carbon::parse($presensiHariIni->jam)->format('H:i') }} 
                dengan status: <strong>{{ $presensiHariIni->status }}</strong>
            </p>
        @else
            <p class="mt-2 text-red-600">❌ Kamu belum melakukan presensi hari ini.</p>
        @endif
    </div>

    <!-- Grafik dan Donut Chart berdampingan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grafik Presensi -->
        <div class="bg-white shadow rounded-2xl p-4 h-full">
            <h2 class="text-lg font-semibold text-[#023392] mb-4">Grafik 7 Hari Terakhir</h2>
            <div class="h-[300px] md:h-[280px]">
                <canvas id="grafikPresensi" class="w-full h-full"></canvas>
            </div>
        </div>

        <!-- Donut Total -->
        <div class="bg-white shadow rounded-2xl p-4 h-full">
            <h2 class="text-lg font-semibold text-[#023392] mb-4">Total Presensi Keseluruhan</h2>
            <div class="flex justify-center items-center h-[300px] md:h-[280px]">
                <canvas id="donutPresensi" class="w-[220px] h-[220px] md:w-[200px] md:h-[200px]"></canvas>
            </div>
        </div>
    </div>

    <!-- Map Presensi Terakhir -->
    @if($presensiTerakhir && $presensiTerakhir->latitude && $presensiTerakhir->longitude)
    <div class="bg-white shadow rounded-2xl p-4">
        <h2 class="text-lg font-semibold text-[#023392] mb-4">Lokasi Presensi Terakhir</h2>
        <div class="rounded-xl overflow-hidden h-64">
            <div id="map" class="w-full h-full"></div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
    // Grafik Presensi
    const data = @json($grafikPresensi);
    const labels = data.map(item => item.tanggal);
    const status = data.map(item => {
        switch (item.status) {
            case 'Hadir': return 1;
            case 'Izin': return 0.5;
            case 'Sakit': return 0.5;
            default: return 0;
        }
    });

    new Chart(document.getElementById('grafikPresensi'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Status',
                data: status,
                backgroundColor: status.map(val =>
                    val === 1 ? '#16a34a' : (val === 0.5 ? '#f59e0b' : '#dc2626')
                ),
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: val => val === 1 ? 'Hadir' : val === 0.5 ? 'Izin/Sakit' : 'Alpha',
                        stepSize: 0.5,
                        beginAtZero: true,
                        max: 1
                    }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

    // Donut Chart
    const donutData = @json($total);
    new Chart(document.getElementById('donutPresensi'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin/Sakit', 'Alpha'],
            datasets: [{
                data: [donutData.hadir, donutData.izin, donutData.alpha],
                backgroundColor: ['#16a34a', '#f59e0b', '#dc2626'],
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } },
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Leaflet Map
    @if($presensiTerakhir && $presensiTerakhir->latitude && $presensiTerakhir->longitude)
    const map = L.map('map').setView([{{ $presensiTerakhir->latitude }}, {{ $presensiTerakhir->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    L.marker([{{ $presensiTerakhir->latitude }}, {{ $presensiTerakhir->longitude }}]).addTo(map)
        .bindPopup("Presensi Terakhir: {{ \Carbon\Carbon::parse($presensiTerakhir->tanggal)->translatedFormat('d M Y') }}")
        .openPopup();
    @endif
</script>
@endpush
