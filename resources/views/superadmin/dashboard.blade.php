@extends('layouts.appsuperadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-10">

    <!-- Heading -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] bg-clip-text text-transparent">
            Superadmin Dashboard
        </h1>        
        <p class="text-sm text-gray-600 mt-1">Selamat datang kembali, berikut ringkasan sistem.</p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Total Admin</h2>
                <p class="text-3xl font-bold">{{ $jumlahAdmin }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-user-shield text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Total User</h2>
                <p class="text-3xl font-bold">{{ $jumlahUser }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl p-4 shadow flex items-center justify-between transform transition-transform hover:scale-105">
            <div>
                <h2 class="text-sm">Total Presensi</h2>
                <p class="text-3xl font-bold">{{ $totalPresensi }}</p>
            </div>
            <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
        <!-- Bar Chart -->
        <div class="bg-white p-6 rounded-2xl shadow h-[350px]">
            <h2 class="text-lg font-semibold mb-4">Grafik Presensi 6 Bulan Terakhir</h2>
            <canvas id="presensiChart" class="w-full h-full"></canvas>
        </div>

        <!-- Pie Chart -->
        <div class="bg-white p-6 rounded-2xl shadow h-[350px]">
            <h2 class="text-lg font-semibold mb-4">Distribusi Role User</h2>
            <div class="flex justify-center items-center h-[260px]">
                <canvas id="roleChart" class="w-[240px] h-[240px]"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Bar Chart
    new Chart(document.getElementById('presensiChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($presensiBulanan->keys()) !!},
            datasets: [{
                label: 'Jumlah Presensi',
                data: {!! json_encode($presensiBulanan->values()) !!},
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
    new Chart(document.getElementById('roleChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($roleDistribution->keys()) !!},
        datasets: [{
            data: {!! json_encode($roleDistribution->values()) !!},
            backgroundColor: [
                '#2563EB', // admin - biru
                '#22C55E', // user - hijau
                '#F59E0B'  // superadmin - kuning/oranye
            ],
            hoverOffset: 4
        }]
    },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
