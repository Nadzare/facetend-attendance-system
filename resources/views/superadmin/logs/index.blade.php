@extends('layouts.appsuperadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Riwayat Aktivitas
        </h1>
        <p class="text-sm text-gray-600 mt-1">Catatan semua aktivitas yang dilakukan oleh admin di sistem FaceTend.</p>
    </div>

    <!-- Log Table -->
    <div class="bg-white shadow-md rounded-xl overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Admin</th>
                    <th class="px-4 py-3 text-left">Aktivitas</th>
                    <th class="px-4 py-3 text-left">Subjek</th>
                    <th class="px-4 py-3 text-left">IP</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-4 py-3 font-semibold text-blue-700">{{ $log->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $log->activity }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $log->subjek ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $log->ip ?? '-' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500 italic">Belum ada log aktivitas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
