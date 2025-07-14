@extends('layouts.appuser')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Riwayat Presensi
        </h2>
        <p class="text-sm text-gray-600 mt-1">Lihat riwayat presensi kamu secara detail berdasarkan waktu dan lokasi.</p>
    </div>

    <!-- Flash -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Jam</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($presensi as $row)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $row->jam }}</td>
                        <td class="px-4 py-3">
                            @if($row->status === 'Hadir')
                                <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded">
                                    Hadir
                                </span>
                            @elseif($row->status === 'Izin')
                                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded">
                                    Izin
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-700 text-xs px-3 py-1 rounded">
                                    Alpha
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="lihatPeta('{{ $row->lokasi }}')" class="text-blue-600 hover:underline text-sm font-medium">
                                Lihat Lokasi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 italic">Belum ada data presensi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Map -->
    <div id="mapModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded p-4 shadow-lg max-w-md w-full relative">
            <button onclick="tutupMap()" class="absolute top-2 right-2 text-gray-500 hover:text-black">✖</button>
            <h3 class="text-lg font-semibold mb-2 text-[#023392]">Lokasi Presensi</h3>
            <div id="map" class="w-full h-64 rounded shadow-inner"></div>
        </div>
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    let map;

    function lihatPeta(lokasi) {
        if (!lokasi) return;

        const [lat, lon] = lokasi.split(',');
        document.getElementById('mapModal').classList.remove('hidden');

        setTimeout(() => {
            if (!map) {
                map = L.map('map').setView([lat, lon], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);
            } else {
                map.setView([lat, lon], 16);
            }

            L.marker([lat, lon]).addTo(map)
                .bindPopup('Lokasi presensi')
                .openPopup();
        }, 100);
    }

    function tutupMap() {
        document.getElementById('mapModal').classList.add('hidden');
        if (map) {
            map.eachLayer(layer => {
                if (layer instanceof L.Marker) map.removeLayer(layer);
            });
        }
    }
</script>
@endsection
