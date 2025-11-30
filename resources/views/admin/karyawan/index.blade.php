@extends('layouts.appadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Data Karyawan
        </h1>
        <p class="text-sm text-gray-600 mt-1">Daftar karyawan terdaftar dalam sistem FaceTend.</p>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4 w-full">
            <!-- Search Input -->
            <input type="text" id="searchInput" placeholder="Cari nama atau NIK..."
                   class="w-full md:w-64 border border-gray-300 rounded px-4 py-2 shadow focus:ring focus:ring-blue-100">

            <!-- Filter Jabatan -->
            <select id="filterJabatan"
                    class="w-full md:w-52 border border-gray-300 rounded px-4 py-2 shadow">
                <option value="">Semua Jabatan</option>
                @foreach($karyawans->pluck('jabatan')->unique() as $jabatan)
                    <option value="{{ strtolower($jabatan) }}">{{ $jabatan }}</option>
                @endforeach
            </select>
        </div>

            <!-- Tambah Karyawan Button (di atas kanan tabel) -->
    <div class="flex justify-end">
        <a href="{{ route('admin.karyawan.create') }}"
           class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg shadow whitespace-nowrap">
            <i class="fas fa-plus-circle"></i>
            <span>Tambah Karyawan</span>
        </a>
    </div>
    </div>



    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Foto</th>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">NIK</th>
                    <th class="py-3 px-4 text-left">Jabatan</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($karyawans->reverse()->values() as $index => $karyawan)
                    <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">
                            @if ($karyawan->foto_wajah)
                                @php
                                    // Pastikan path tidak dobel 'storage/'
                                    $fotoPath = str_starts_with($karyawan->foto_wajah, 'storage/') 
                                        ? $karyawan->foto_wajah 
                                        : 'storage/' . $karyawan->foto_wajah;
                                @endphp
                                <img src="{{ asset($fotoPath) }}"
                                     alt="Foto {{ $karyawan->nama }}"
                                     class="w-12 h-12 object-cover rounded-full shadow border border-gray-300 cursor-pointer hover:opacity-80 transition"
                                     onclick="showImageModal('{{ asset($fotoPath) }}', '{{ $karyawan->nama }}')"
                                     onerror="this.src='{{ asset('starindo.png') }}'; this.classList.remove('rounded-full'); this.classList.add('rounded');">
                            @else
                                <span class="text-gray-400 italic text-xs">Belum ada</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-semibold">{{ $karyawan->nama }}</td>
                        <td class="py-3 px-4">{{ $karyawan->nik }}</td>
                        <td class="py-3 px-4">{{ $karyawan->jabatan }}</td>
                        <td class="py-3 px-4">{{ $karyawan->user->email ?? '-' }}</td>
                        <td class="py-3 px-4 flex items-center gap-2">
                            <a href="{{ route('admin.karyawan.edit', $karyawan) }}"
                               class="inline-flex items-center px-3 py-1 text-xs bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.karyawan.destroy', $karyawan->id) }}" method="POST" class="form-hapus">
  @csrf
  @method('DELETE')

                                <button type="submit"
                                        class="inline-flex items-center px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded shadow">
                                    <i class="fas fa-trash-alt mr-1"></i>Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500 italic">Tidak ada data karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
    // Search and filter function
    const searchInput = document.getElementById('searchInput');
    const filterJabatan = document.getElementById('filterJabatan');

    function filterTable() {
        const keyword = searchInput.value.toLowerCase();
        const jabatan = filterJabatan.value.toLowerCase();

        document.querySelectorAll("tbody tr").forEach(row => {
            const rowText = row.innerText.toLowerCase();
            const jabatanText = row.querySelector("td:nth-child(5)")?.innerText.toLowerCase();

            const matchesSearch = rowText.includes(keyword);
            const matchesJabatan = !jabatan || jabatanText === jabatan;

            row.style.display = matchesSearch && matchesJabatan ? "" : "none";
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterJabatan.addEventListener('change', filterTable);

    // Image modal functions
    function showImageModal(imageSrc, caption) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const modalCaption = document.getElementById('modalCaption');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalImg.src = imageSrc;
        modalCaption.textContent = 'Foto: ' + caption;
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        icon : 'success',
        text: '{{ session('success') }}',
        confirmButtonColor: '#2563eb',
    });
</script>
@endif

<script>
  document.querySelectorAll('.form-hapus').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault(); // cegah submit langsung

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit(); // kirim form setelah konfirmasi
        }
      });
    });
  });
</script>
@endpush
