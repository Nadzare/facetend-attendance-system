@extends('layouts.appuser')

@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- Header & Tombol -->
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
        <div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
                Pengajuan Izin/Sakit
            </h2>
            <p class="text-sm text-gray-600 mt-1">Lihat pengajuan izin/sakit kamu di bawah ini.</p>
        </div>
        <a href="{{ route('izin.create') }}"
           class="inline-block bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-5 py-2 rounded-lg shadow text-sm font-medium">
            + Ajukan Izin / Sakit
        </a>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabel -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Jenis</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Alasan</th>
                    <th class="py-3 px-4 text-left">Bukti</th>
                    <th class="py-3 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($izin as $item)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $item->jenis }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td class="px-4 py-3">{{ $item->alasan }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ asset('storage/' . $item->bukti) }}" target="_blank"
                               class="text-blue-600 hover:underline text-sm">Lihat Bukti</a>
                        </td>
                        <td class="px-4 py-3">
                            @if($item->status === 'Pending')
                                <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded">
                                    Pending
                                </span>
                            @elseif($item->status === 'Disetujui')
                                <span class="inline-block bg-green-100 text-green-800 text-xs px-3 py-1 rounded">
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-block bg-red-100 text-red-800 text-xs px-3 py-1 rounded">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 italic">
                            Belum ada data pengajuan izin atau sakit.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
