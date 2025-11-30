@extends('layouts.appadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Data Presensi Karyawan
        </h1>
        <p class="text-sm text-gray-600 mt-1">Riwayat presensi karyawan berdasarkan tanggal, jabatan, dan lokasi.</p>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Form -->
    <form method="GET" id="filterForm" class="grid md:grid-cols-3 gap-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 filter-trigger">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="jabatan" value="{{ request('jabatan') }}" placeholder="Contoh: Operator"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 filter-trigger">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="lokasi" value="{{ request('lokasi') }}" placeholder="Contoh: Pabrik A"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 filter-trigger">
        </div>

        <div class="md:col-span-3 flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.presensi.index') }}"
            class="inline-block text-sm text-gray-600 hover:underline px-5 py-2 rounded-lg border border-gray-300 bg-white shadow">
                Reset
            </a>
            <button type="submit"
                    class="inline-block bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white px-5 py-2 rounded-lg shadow text-sm font-medium">
                Filter
            </button>
        </div>

    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Jabatan</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Jam</th>
                    <th class="py-3 px-4 text-left">Koordinat</th>
                    <th class="py-3 px-4 text-left">Alamat</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Foto</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($presensis as $presensi)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="px-4 py-3">{{ $presensi->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $presensi->user->karyawan->jabatan ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $presensi->tanggal }}</td>
                        <td class="px-4 py-3">{{ $presensi->jam }}</td>
                        <td class="px-4 py-3">{{ $presensi->lokasi }}</td>
                        <td class="px-4 py-3">{{ $presensi->alamat_lengkap ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded">
                                {{ $presensi->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($presensi->foto)
                                @php
                                    // Pastikan path tidak dobel 'storage/'
                                    $fotoPath = str_starts_with($presensi->foto, 'storage/') 
                                        ? $presensi->foto 
                                        : 'storage/' . $presensi->foto;
                                @endphp
                                <a href="#" onclick="showImageModal('{{ asset($fotoPath) }}', '{{ $presensi->user->name ?? 'Unknown' }} - {{ $presensi->tanggal }}'); return false;"
                                   class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 hover:underline text-sm">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500 italic">
                            Tidak ada data presensi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Export Buttons -->
<!-- Export Buttons -->
<div class="flex justify-end gap-3 mt-4">
    <a href="{{ route('admin.presensi.export', request()->query()) }}"
       class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white px-5 py-2 rounded-lg shadow text-sm font-medium">
        <i class="fas fa-file-excel"></i>
        Export Excel
    </a>

    <a href="{{ route('admin.presensi.export.txt', request()->query()) }}"
       class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-800 hover:to-gray-700 text-white px-5 py-2 rounded-lg shadow text-sm font-medium">
        <i class="fas fa-file-alt"></i>
        Export TXT
    </a>
</div>


    <!-- Pagination -->
    <div class="mt-6">
        {{ $presensis->withQueryString()->links() }}
    </div>
</div>

<!-- Modal Preview Foto -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-3xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
        <p id="modalCaption" class="text-white text-center mt-2 text-sm"></p>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Filter form auto-submit
    document.querySelectorAll('.filter-trigger').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });

    // Image modal functions
    function showImageModal(imageSrc, caption) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalImg.src = imageSrc;
        modalCaption.textContent = 'Foto Presensi: ' + caption;
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endpush
