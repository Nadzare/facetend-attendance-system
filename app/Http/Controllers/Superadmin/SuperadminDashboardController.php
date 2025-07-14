<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperadminDashboardController extends Controller
{
    public function index()
    {
        $jumlahAdmin = User::where('role', 'admin')->count();
        $jumlahUser = User::where('role', 'user')->count();
        $totalPresensi = Presensi::count();
        
        $roleDistribution = User::select('role', DB::raw('count(*) as total'))
        ->groupBy('role')
        ->pluck('total', 'role');

        // Presensi per bulan (6 bulan terakhir)
        $presensiBulanan = Presensi::selectRaw("
                DATE_FORMAT(tanggal, '%Y-%m') as bulan_key,
                DATE_FORMAT(tanggal, '%M') as bulan,
                COUNT(*) as total
            ")
            ->where('tanggal', '>=', Carbon::now()->subMonths(6))
            ->groupBy('bulan_key', 'bulan')
            ->orderBy('bulan_key')
            ->pluck('total', 'bulan');

        return view('superadmin.dashboard', compact(
            'jumlahAdmin',
            'jumlahUser',
            'totalPresensi',
            'presensiBulanan',
            'roleDistribution'
        ));

    }
}
