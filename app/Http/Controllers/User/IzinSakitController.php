<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IzinSakit;
use Illuminate\Support\Facades\Auth;

class IzinSakitController extends Controller
{
    // ✅ Menampilkan daftar izin user
    public function index()
    {
        $izin = IzinSakit::where('user_id', Auth::id())->latest()->get();
        return view('user.izin.index', compact('izin'));
    }

    // ✅ Form pengajuan izin
    public function create()
    {
        return view('user.izin.create');
    }

    // ✅ Simpan pengajuan izin
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:Izin,Sakit',
            'tanggal' => 'required|date',
            'alasan' => 'required|string',
            'bukti' => 'required|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $path = $request->file('bukti')->store('bukti-izin', 'public');

        IzinSakit::create([
            'user_id' => Auth::id(),
            'jenis' => $request->jenis,
            'tanggal' => $request->tanggal,
            'alasan' => $request->alasan,
            'bukti' => $path,
            'status' => 'Pending',
        ], [
        'required' => 'Field :attribute wajib diisi.',
    ]);

        return redirect()->back()->with('success', 'Pengajuan izin berhasil!');

    }

    // ✅ Menampilkan riwayat izin user
    public function riwayat()
    {
        $izin = IzinSakit::where('user_id', Auth::id())
                    ->orderByDesc('tanggal')
                    ->get();

        return view('user.izin.riwayat', compact('izin'));
    }
}
