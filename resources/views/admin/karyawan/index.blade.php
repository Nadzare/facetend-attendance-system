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
                                <img src="{{ asset('storage/' . $karyawan->foto_wajah) }}"
                                     alt="Foto Wajah"
                                     class="w-12 h-12 object-cover rounded-full shadow border border-gray-300">
                            @else
                                <span class="text-gray-400 italic">Belum ada</span>
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
                            <form action="{{ route('admin.karyawan.destroy', $karyawan) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
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
@endsection

@push('scripts')
<script>
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
</script>
@endpush
