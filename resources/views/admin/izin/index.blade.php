@extends('layouts.appadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">

    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Daftar Permohonan Izin / Sakit
        </h1>
        <p class="text-sm text-gray-600 mt-1">Kelola permohonan izin dan sakit dari karyawan FaceTend.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Jenis</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($izin as $item)
                    <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                        <td class="py-3 px-4 font-medium">{{ $item->user->name }}</td>
                        <td class="py-3 px-4">{{ $item->jenis }}</td>
                        <td class="py-3 px-4">{{ $item->tanggal }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $item->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($item->status == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.izin.show', $item->id) }}"
                               class="text-blue-600 hover:underline text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 italic">Tidak ada permohonan izin atau sakit.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Ditolak!',
        text: '{{ session('error') }}',
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

@endsection
