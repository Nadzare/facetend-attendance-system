@extends('layouts.appsuperadmin')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-gradient bg-gradient-to-r from-[#023392] to-[#266EAF] text-transparent bg-clip-text">
            Data Admin
        </h1>
        <p class="text-sm text-gray-600 mt-1">Daftar admin yang terdaftar di sistem FaceTend.</p>
    </div>

    <!-- Search & Tambah -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4 w-full">
            <form method="GET" action="{{ route('superadmin.admin.admin-management.index') }}" class="w-full flex gap-2">
                <input type="text" name="search" placeholder="Cari nama atau email..."
                       value="{{ request('search') }}"
                       class="w-full md:w-64 border border-gray-300 rounded px-4 py-2 shadow focus:ring focus:ring-blue-100 text-sm">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow text-sm">
                    🔍 Cari
                </button>
            </form>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('superadmin.admin.admin-management.create') }}"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg shadow whitespace-nowrap text-sm">
                <i class="fas fa-plus-circle"></i> Tambah Admin
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white shadow rounded-xl overflow-x-auto mt-4">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gradient-to-r from-[#023392] to-[#266EAF] text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                    <th class="py-3 px-4 text-left">Reset Password</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                        <td class="py-3 px-4 font-semibold">{{ $admin->name }}</td>
                        <td class="py-3 px-4">{{ $admin->email }}</td>
                        <td class="py-3 px-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('superadmin.admin.admin-management.edit', $admin->id) }}"
                                   class="inline-flex items-center px-3 py-1 text-xs bg-indigo-600 hover:bg-indigo-700 text-white rounded shadow">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <form action="{{ route('superadmin.admin.admin-management.destroy', $admin->id) }}"
                                      method="POST" onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1 text-xs bg-red-600 hover:bg-red-700 text-white rounded shadow">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <form action="{{ route('superadmin.admin.admin-management.reset-password', $admin->id) }}"
                                  method="POST" class="flex flex-col sm:flex-row sm:items-center gap-2">
                                @csrf
                                <input type="password" name="password" placeholder="Password baru" required
                                       class="border border-gray-300 rounded px-2 py-1 text-xs w-full sm:w-32">
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi" required
                                       class="border border-gray-300 rounded px-2 py-1 text-xs w-full sm:w-32">
                                <button type="submit"
                                        class="text-green-600 hover:underline text-sm whitespace-nowrap">
                                    🔄 Reset
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 italic">Belum ada admin yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $admins->links() }}
    </div>
</div>
@endsection
