@extends('layouts.appadmin')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">📋 Daftar Permohonan Izin/Sakit</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <table class="w-full table-auto border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Nama</th>
                <th class="p-2">Jenis</th>
                <th class="p-2">Tanggal</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($izin as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->user->name }}</td>
                    <td class="p-2">{{ $item->jenis }}</td>
                    <td class="p-2">{{ $item->tanggal }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded {{ $item->status == 'Pending' ? 'bg-yellow-100 text-yellow-700' : ($item->status == 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="p-2">
                        <a href="{{ route('admin.izin.show', $item->id) }}" class="text-blue-600 underline">Detail</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
