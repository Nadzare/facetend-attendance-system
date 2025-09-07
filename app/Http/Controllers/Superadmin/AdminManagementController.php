<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $admins = $query->paginate(10)->withQueryString();

        return view('superadmin.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'admin',
        ]);

        $this->logActivity('Menambahkan admin baru', $admin->name, $request);

        return redirect()->route('superadmin.admin.admin-management.index')->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit(User $admin)
    {
        return view('superadmin.admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $admin->id,
        ]);

        $admin->update($request->only('name', 'email'));

        $this->logActivity('Memperbarui data admin', $admin->name, $request);

        return redirect()->route('superadmin.admin.admin-management.index')->with('success', 'Admin berhasil diperbarui!');
    }
    public function destroy(Request $request, User $admin)
    {
        $adminName = $admin->name;
        $admin->delete();

        $this->logActivity('Menghapus admin', $adminName, $request);

        return redirect()->route('superadmin.admin.admin-management.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function resetPassword(Request $request, User $admin)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $admin->update([
            'password' => bcrypt($request->password),
        ]);

        $this->logActivity('Mereset password admin', $admin->name, $request);

        return redirect()->back()->with('success', 'Password berhasil direset.');
    }

    /**
     * Catat log aktivitas superadmin.
     */
    private function logActivity($activity, $subjek = null, Request $request)
    {
        ActivityLog::create([
            'user_id'  => Auth::id(),
            'activity' => $activity,
            'subjek'   => $subjek,
            'ip'       => $request->ip(),
        ]);
    }
}
