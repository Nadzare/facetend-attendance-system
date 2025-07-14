<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IzinSakit;

class IzinSakitController extends Controller
{
    public function index()
    {
        $izin = IzinSakit::latest()->get();
        return view('admin.izin.index', compact('izin'));
    }

    public function show($id)
    {
        $data = IzinSakit::findOrFail($id);
        return view('admin.izin.show', compact('data'));
    }

    public function approve($id)
    {
        $izin = IzinSakit::findOrFail($id);
        $izin->status = 'Disetujui';
        $izin->save();

        return redirect()->route('admin.izin.index')->with('success', 'Permohonan disetujui.');
    }

    public function reject($id)
    {
        $izin = IzinSakit::findOrFail($id);
        $izin->status = 'Ditolak';
        $izin->save();

        return redirect()->route('admin.izin.index')->with('error', 'Permohonan ditolak.');
    }
}