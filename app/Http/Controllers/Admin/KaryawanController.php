<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('user')->latest()->get();
        return view('admin.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->doesntHave('karyawan')->get();
        return view('admin.karyawan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'nama' => 'required',
            'nik' => 'required',
            'jabatan' => 'required',
            'foto_base64' => 'required',
        ]);

        $data = $request->only('user_id', 'nama', 'nik', 'jabatan');

        if ($request->has('foto_base64')) {
            $fotoBase64 = $request->input('foto_base64');
            $fotoContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $fotoBase64));
            $namaFile = 'wajah_' . time() . '.jpg';
            $fotoPath = 'wajah_karyawan/' . $namaFile;

            Storage::disk('public')->put($fotoPath, $fotoContent);
            $data['foto_wajah'] = $fotoPath;

            // Kirim ke Face++ untuk ambil face_token
            try {
                $response = Http::asMultipart()->post('https://api-us.faceplusplus.com/facepp/v3/detect', [
                    'api_key'      => env('FACE_API_KEY'),
                    'api_secret'   => env('FACE_API_SECRET'),
                    'image_base64' => base64_encode($fotoContent),
                ]);

                Log::info('FACE++ DETECT RESPONSE:', $response->json());

                if ($response->successful() && isset($response['faces'][0]['face_token'])) {
                    $data['face_token'] = $response['faces'][0]['face_token'];
                } else {
                    Log::error('FACE++ gagal deteksi wajah atau tidak ada face_token', $response->json());
                }
            } catch (\Exception $e) {
                Log::error('Error saat kirim ke Face++: ' . $e->getMessage());
            }
        }

        Karyawan::create($data);

        return redirect()->route('admin.karyawan.index')->with('success', '✅ Karyawan berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('admin.karyawan.edit', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:karyawans,nik,' . $karyawan->id,
            'jabatan' => 'required|string|max:255',
        ]);

        $karyawan->update($request->only('nama', 'nik', 'jabatan'));

        return redirect()->route('admin.karyawan.index')->with('success', '✅ Karyawan berhasil diperbarui.');
    }

    public function destroy(Karyawan $karyawan)
    {
        if ($karyawan->foto_wajah && Storage::disk('public')->exists($karyawan->foto_wajah)) {
            Storage::disk('public')->delete($karyawan->foto_wajah);
        }

        $karyawan->delete();

        return redirect()->route('admin.karyawan.index')->with('success', '✅ Karyawan berhasil dihapus.');
    }
}
