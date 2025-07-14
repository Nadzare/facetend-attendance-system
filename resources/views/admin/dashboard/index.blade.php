@extends('layouts.appadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-10">

    <div class="mb-6">
    <h1 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent">
        Admin Dashboard
    </h1>        
        <p class="text-sm text-gray-600 mt-1">Selamat datang kembali, berikut ringkasan data hari ini.</p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Karyawan</h2>
                <p class="text-3xl font-bold">{{ $totalKaryawan }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Presensi Hari Ini</h2>
                <p class="text-3xl font-bold">{{ $totalPresensiHariIni }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Lokasi Terdaftar</h2>
                <p class="text-3xl font-bold">{{ $totalLokasi }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-map-marker-alt text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Total Jabatan</h2>
                <p class="text-3xl font-bold">{{ $totalJabatan }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-briefcase text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Grafik Presensi & Kehadiran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow h-[350px]">
            <h2 class="text-lg font-semibold mb-4">Grafik Presensi 7 Hari Terakhir</h2>
            <canvas id="presensiChart" class="w-full h-full"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-2xl shadow h-[350px]">
            <h2 class="text-lg font-semibold mb-4">Persentase Kehadiran Hari Ini</h2>
            <div class="flex justify-center items-center h-[260px]">
                <canvas id="kehadiranPieChart" class="w-[240px] h-[240px]"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart
    new Chart(document.getElementById('presensiChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($presensiChart->pluck('tanggal')) !!},
            datasets: [{
                label: 'Jumlah Presensi',
                data: {!! json_encode($presensiChart->pluck('jumlah')) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('kehadiranPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Tidak Hadir'],
            datasets: [{
                data: [{{ $jumlahHadir }}, {{ $jumlahTidakHadir }}],
                backgroundColor: ['#10B981', '#F87171'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });
</script>
@endpush
@endsection
