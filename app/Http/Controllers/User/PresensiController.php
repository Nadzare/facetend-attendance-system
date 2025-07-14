<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Presensi;

class PresensiController extends Controller
{
    public function index()
    {
        return view('user.presensi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_selfie' => 'required',
            'lokasi' => 'required',
            'alamat_lengkap' => 'nullable',
            'akurasi' => 'nullable|numeric'
        ]);

        $user = Auth::user();
        $karyawan = $user->karyawan;

        if (!$karyawan || !$karyawan->face_token) {
            return back()->with('error', 'Data wajah tidak tersedia. Hubungi admin.');
        }

        // Simpan foto selfie
        $foto = $request->foto_selfie;
        $fotoContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto));
        $namaFile = 'selfie_' . time() . '.jpg';
        $pathFoto = 'selfie/' . $namaFile;
        Storage::disk('public')->put($pathFoto, $fotoContent);

        // Verifikasi wajah (Face++)
        $match = $this->compareFaces($fotoContent, $karyawan->face_token);
        if (!$match) {
            return back()->with('error', '❌ Wajah tidak cocok dengan data terdaftar.');
        }

        // // Validasi akurasi minimal (misal 100 meter)
        // if ($request->akurasi && $request->akurasi > 100) {
        //     return back()->with('error', '⚠️ Sinyal GPS kurang akurat. Silakan pindah lokasi dan coba lagi.');
        // }

        // Simpan data presensi
        Presensi::create([
            'user_id' => $user->id,
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'foto' => $pathFoto,
            'lokasi' => $request->lokasi,
            'alamat_lengkap' => $request->alamat_lengkap,
            'akurasi' => $request->akurasi, // simpan akurasi
            'status' => 'Hadir',
        ]);

        return redirect()->back()->with('success', '✅ Presensi berhasil!');
    }

    private function compareFaces($imageSelfie, $faceTokenRegistered)
    {
        $apiKey = env('FACE_API_KEY');
        $apiSecret = env('FACE_API_SECRET');

        $response = Http::asMultipart()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'image_base64_1' => base64_encode($imageSelfie),
            'face_token2' => $faceTokenRegistered,
        ]);

        Log::info('FACE++ COMPARE RESPONSE:', $response->json());

        if ($response->successful() && isset($response['confidence'])) {
            $confidence = $response['confidence'];
            Log::info('Confidence Score: ' . $confidence);
            return $confidence >= 70;
        }

        Log::error('❌ Gagal membandingkan wajah atau tidak ada confidence.');
        return false;
    }


    public function riwayat()
    {
        $presensi = Presensi::where('user_id', Auth::id())
                        ->orderByDesc('tanggal')
                        ->get();

        return view('user.presensi.riwayat', compact('presensi'));
    }

}
