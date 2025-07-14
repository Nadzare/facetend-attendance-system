<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;
use App\Models\IzinSakit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // ✅ 1. Presensi hari ini
        $presensiHariIni = Presensi::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        // ✅ 2. Rekap Kehadiran Mingguan (Senin - Minggu)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $hadir = Presensi::where('user_id', $user->id)
            ->where('status', 'Hadir')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->count();

        $izin = IzinSakit::where('user_id', $user->id)
            ->where('status', 'Disetujui')
            ->where('jenis', 'Izin')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->count();

        $sakit = IzinSakit::where('user_id', $user->id)
            ->where('status', 'Disetujui')
            ->where('jenis', 'Sakit')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->count();

        // Hitung Alpha
        $alpha = 0;
        $periode = CarbonPeriod::create($startOfWeek, $endOfWeek);
        foreach ($periode as $tanggal) {
            $tgl = $tanggal->toDateString();

            $adaPresensi = Presensi::where('user_id', $user->id)
                ->where('tanggal', $tgl)
                ->exists();

            $adaIzin = IzinSakit::where('user_id', $user->id)
                ->where('tanggal', $tgl)
                ->where('status', 'Disetujui')
                ->exists();

            if (!$adaPresensi && !$adaIzin) {
                $alpha++;
            }
        }

        $rekap = [
            'hadir' => $hadir,
            'izin'  => $izin,
            'sakit' => $sakit,
            'alpha' => $alpha,
        ];

        // ✅ 3. Presensi terakhir
        $presensiTerakhir = Presensi::where('user_id', $user->id)
            ->latest()
            ->first();

        // ✅ 4. Grafik Presensi 7 Hari Terakhir
        $grafikPresensi = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->toDateString();

            $status = Presensi::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->value('status');

            if (!$status) {
                $izinStatus = IzinSakit::where('user_id', $user->id)
                    ->whereDate('tanggal', $tanggal)
                    ->where('status', 'Disetujui')
                    ->first();

                $status = $izinStatus ? $izinStatus->jenis : 'Alpha';
            }

            $grafikPresensi->push([
                'tanggal' => Carbon::parse($tanggal)->translatedFormat('d M'),
                'status' => $status,
            ]);
        }

        // ✅ 5. Donut Chart Total Presensi Keseluruhan
        $total = [
            'hadir' => Presensi::where('user_id', $user->id)->where('status', 'Hadir')->count(),
            'izin'  => IzinSakit::where('user_id', $user->id)->where('status', 'Disetujui')->where('jenis', 'Izin')->count(),
            'sakit' => IzinSakit::where('user_id', $user->id)->where('status', 'Disetujui')->where('jenis', 'Sakit')->count(),
            'alpha' => 0,
        ];

        // Hitung total alpha keseluruhan
        $firstDate = Presensi::where('user_id', $user->id)->orderBy('tanggal')->first()?->tanggal
            ?? IzinSakit::where('user_id', $user->id)->where('status', 'Disetujui')->orderBy('tanggal')->first()?->tanggal
            ?? now()->startOfMonth()->toDateString();

        $fullPeriod = CarbonPeriod::create(Carbon::parse($firstDate), now());

        foreach ($fullPeriod as $tanggal) {
            $tgl = $tanggal->toDateString();
            $adaPresensi = Presensi::where('user_id', $user->id)->where('tanggal', $tgl)->exists();
            $adaIzin = IzinSakit::where('user_id', $user->id)->where('tanggal', $tgl)->where('status', 'Disetujui')->exists();
            if (!$adaPresensi && !$adaIzin) {
                $total['alpha']++;
            }
        }

        return view('user.dashboard', compact(
            'presensiHariIni',
            'rekap',
            'presensiTerakhir',
            'grafikPresensi',
            'total'
        ));
    }
}
