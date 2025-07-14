<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total data
        $totalKaryawan = Karyawan::count();
        $totalPresensiHariIni = Presensi::whereDate('tanggal', today())->count();
        $totalLokasi = Presensi::distinct('lokasi')->count('lokasi');
        $totalJabatan = DB::table('karyawans')->distinct('jabatan')->count('jabatan');

        // Grafik: Presensi 7 Hari Terakhir
        $dates = collect(range(0, 6))
            ->map(fn($i) => now()->subDays($i)->format('Y-m-d'))
            ->reverse();

        $presensiChart = $dates->map(function ($date) {
            return [
                'tanggal' => Carbon::parse($date)->format('d M'),
                'jumlah' => Presensi::whereDate('tanggal', $date)->count(),
            ];
        });

        // Pie Chart: Hadir vs Tidak Hadir Hari Ini
        $jumlahHadir = Presensi::whereDate('tanggal', today())->count();
        $jumlahTidakHadir = $totalKaryawan - $jumlahHadir;

        return view('admin.dashboard.index', compact(
            'totalKaryawan',
            'totalPresensiHariIni',
            'totalLokasi',
            'totalJabatan',
            'presensiChart',
            'jumlahHadir',
            'jumlahTidakHadir'
        ));
    }
}
