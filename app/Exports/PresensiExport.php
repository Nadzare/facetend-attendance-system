<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PresensiExport implements FromCollection, WithHeadings
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = Presensi::with('user.karyawan')->latest();

        if ($this->filter['tanggal']) {
            $query->where('tanggal', $this->filter['tanggal']);
        }

        if ($this->filter['jabatan']) {
            $query->whereHas('user.karyawan', function ($q) {
                $q->where('jabatan', $this->filter['jabatan']);
            });
        }

        if ($this->filter['lokasi']) {
            $query->where('lokasi', 'like', '%' . $this->filter['lokasi'] . '%');
        }

        return $query->get()->map(function ($p) {
            return [
                'Nama'     => $p->user->name ?? '-',
                'Jabatan'  => $p->user->karyawan->jabatan ?? '-',
                'Tanggal'  => $p->tanggal,
                'Jam'      => $p->jam,
                'Lokasi'   => $p->lokasi,
                'Status'   => $p->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Jabatan', 'Tanggal', 'Jam', 'Lokasi', 'Status'];
    }
}
