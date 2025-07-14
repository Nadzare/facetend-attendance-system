@extends('layouts.appadmin')

@section('content')
<div class="max-w-xl mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">📄 Detail Permohonan</h2>

    <div class="space-y-2">
        <p><strong>Nama:</strong> {{ $data->user->name }}</p>
        <p><strong>Jenis:</strong> {{ $data->jenis }}</p>
        <p><strong>Tanggal:</strong> {{ $data->tanggal }}</p>
        <p><strong>Alasan:</strong> {{ $data->alasan }}</p>
        <p><strong>Status:</strong> {{ $data->status }}</p>
        <p><strong>Bukti:</strong></p>
        @if(Str::endsWith($data->bukti, '.pdf'))
            <a href="{{ asset('storage/'.$data->bukti) }}" target="_blank" class="text-blue-600 underline">Lihat PDF</a>
        @else
            <img src="{{ asset('storage/'.$data->bukti) }}" class="w-64 rounded shadow" alt="Bukti Izin">
        @endif
    </div>

    @if($data->status === 'Pending')
    <form action="{{ route('admin.izin.approve', $data->id) }}" method="POST" class="mt-4 inline-block">
        @csrf
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Setujui</button>
    </form>

    <form action="{{ route('admin.izin.reject', $data->id) }}" method="POST" class="mt-4 inline-block ml-2">
        @csrf
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Tolak</button>
    </form>
    @endif
</div>
@endsection
