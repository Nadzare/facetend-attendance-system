<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;

class PresensiController extends Controller
{
    public function index(Request $request)
{
    $presensis = Presensi::with('user');

    // Filter
    if ($request->filled('tanggal')) {
        $presensis->where('tanggal', $request->tanggal);
    }

    if ($request->filled('jabatan')) {
        $presensis->whereHas('user.karyawan', function ($q) use ($request) {
            $q->where('jabatan', $request->jabatan);
        });
    }

    if ($request->filled('lokasi')) {
        $presensis->where('lokasi', 'like', '%' . $request->lokasi . '%');
    }

    $presensis = $presensis->latest()->paginate(10);

    return view('admin.presensi.index', compact('presensis'));
}


public function export(Request $request)
{
    $filter = [
        'tanggal' => $request->tanggal,
        'jabatan' => $request->jabatan,
        'lokasi'  => $request->lokasi,
    ];

    return Excel::download(new PresensiExport($filter), 'presensi.xlsx');
}



public function exportTxt(Request $request)
{
    $query = \App\Models\Presensi::query();

    if ($request->filled('tanggal')) {
        $query->where('tanggal', $request->tanggal);
    }

    if ($request->filled('jabatan')) {
        $query->whereHas('user.karyawan', function ($q) use ($request) {
            $q->where('jabatan', $request->jabatan);
        });
    }

    if ($request->filled('lokasi')) {
        $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
    }

    $presensis = $query->select('id', 'tanggal', 'jam', 'status')->get();

    $lines = [];

    foreach ($presensis as $p) {
        $lines[] = "{$p->id} | {$p->tanggal} | {$p->jam} | {$p->status}";
    }

    $txtContent = implode("\n", $lines);
    $fileName = 'presensi_export_' . now()->format('Ymd_His') . '.txt';

    return Response::make($txtContent, 200, [
        'Content-Type' => 'text/plain',
        'Content-Disposition' => "attachment; filename=$fileName",
    ]);
}
}
