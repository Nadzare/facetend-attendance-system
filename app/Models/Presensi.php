<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

        protected $fillable = [
        'user_id',
        'tanggal',
        'jam',
        'foto',
        'lokasi',
        'status',
        'foto',
        'alamat_lengkap',
    ];

}
