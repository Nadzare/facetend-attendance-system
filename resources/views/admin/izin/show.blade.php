@extends('layouts.appadmin')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white rounded-2xl shadow-lg">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center gap-2">
   
        <span>Detail Permohonan</span>
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
        <div>
            <p class="font-semibold text-gray-600">Nama</p>
            <p class="text-gray-800">{{ $data->user->name }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-600">Jenis Izin</p>
            <p class="text-gray-800">{{ $data->jenis }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-600">Tanggal</p>
            <p class="text-gray-800">{{ $data->tanggal }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-600">Alasan</p>
            <p class="text-gray-800">{{ $data->alasan }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-600">Status</p>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium
                {{ $data->status === 'Approved' ? 'bg-green-100 text-green-700' :
                   ($data->status === 'Rejected' ? 'bg-red-100 text-red-700' :
                   'bg-yellow-100 text-yellow-700') }}">
                {{ $data->status }}
            </span>
        </div>
        <div>
            <p class="font-semibold text-gray-600">Bukti</p>
            @if(Str::endsWith($data->bukti, '.pdf'))
                <a href="{{ asset('storage/'.$data->bukti) }}" target="_blank" class="text-blue-600 underline hover:text-blue-800">
                    Lihat Bukti (PDF)
                </a>
            @else
                <img src="{{ asset('storage/'.$data->bukti) }}" class="w-40 rounded shadow-md mt-1" alt="Bukti Izin">
            @endif
        </div>
    </div>

    @if($data->status === 'Pending')
    <div class="flex items-center gap-3 mt-8">
        <form action="{{ route('admin.izin.approve', $data->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white transition-all shadow flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Setujui
            </button>
        </form>

        <form action="{{ route('admin.izin.reject', $data->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white transition-all shadow flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Tolak
            </button>
        </form>
    </div>
    @endif
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
