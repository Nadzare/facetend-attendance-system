<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\Admin\PresensiController as AdminPresensiController;
use App\Http\Controllers\User\PresensiController as UserPresensiController;
use App\Http\Controllers\Superadmin\AdminManagementController;
use App\Http\Controllers\Superadmin\ActivityLogController;
use App\Http\Controllers\Superadmin\SuperadminDashboardController;
use App\Http\Middleware\RedirectIfAuthenticatedByRole;
use App\Http\Controllers\User\IzinSakitController;
use App\Http\Controllers\User\PresensiController;
use App\Http\Controllers\Admin\IzinSakitController as AdminIzinSakitController;



/*
|--------------------------------------------------------------------------
| Guest Routes (Login & Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Root route redirects to login page
    Route::get('/', function() {
        return redirect('/login');
    })->middleware(RedirectIfAuthenticatedByRole::class);

    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
        ->middleware(RedirectIfAuthenticatedByRole::class)
        ->name('register');
});

/*
|--------------------------------------------------------------------------
| Home Page (public)
|--------------------------------------------------------------------------
*/
// Route::get('/', function () {
//     return view('welcome');
// });

/*
|--------------------------------------------------------------------------
| Profile (auth required)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

/*
|--------------------------------------------------------------------------
| USER ROUTES (/user/…)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('user.dashboard');

    // 📌 Menu Presensi
    Route::get('/presensi', [UserPresensiController::class, 'index'])->name('presensi.create');
    Route::post('/presensi', [UserPresensiController::class, 'store'])->name('presensi.store');

    // 📌 Menu Izin / Sakit
    Route::get('/izin', [\App\Http\Controllers\User\IzinSakitController::class, 'index'])->name('izin.index');
    Route::get('/izin/create', [\App\Http\Controllers\User\IzinSakitController::class, 'create'])->name('izin.create');
    Route::post('/izin', [\App\Http\Controllers\User\IzinSakitController::class, 'store'])->name('izin.store');

    Route::get('/izin/riwayat', [IzinSakitController::class, 'riwayat'])->name('izin.riwayat');
    Route::get('/presensi/riwayat-presensi', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');



        Route::get('/user/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::get('/user/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');

});



/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (/admin/…)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/karyawan', KaryawanController::class);
    
    Route::get('/presensi', [AdminPresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/export', [AdminPresensiController::class, 'export'])->name('presensi.export');
    Route::get('/presensi/export-txt', [AdminPresensiController::class, 'exportTxt'])->name('presensi.export.txt');

    Route::get('/izin', [AdminIzinSakitController::class, 'index'])->name('izin.index');
    Route::get('/izin/{id}', [AdminIzinSakitController::class, 'show'])->name('izin.show');
    Route::post('/izin/{id}/setujui', [AdminIzinSakitController::class, 'approve'])->name('izin.approve');
    Route::post('/izin/{id}/tolak', [AdminIzinSakitController::class, 'reject'])->name('izin.reject');

});

/*
|--------------------------------------------------------------------------
| SUPERADMIN DASHBOARD (/superadmin/…)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

    // Dashboard Superadmin
    Route::get('/dashboard', [SuperadminDashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN MANAGEMENT (Superadmin Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('admin-management', AdminManagementController::class)->parameters([
            'admin-management' => 'admin'
        ]);
        Route::post('admin-management/reset-password/{admin}', [AdminManagementController::class, 'resetPassword'])->name('admin-management.reset-password');
    });

    /*
    |--------------------------------------------------------------------------
    | AUDIT LOG / ACTIVITY LOG (Superadmin Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Superadmin\ActivityLogController::class, 'index'])->name('index');
    });




  /*
    |--------------------------------------------------------------------------
    | LAPORAN PRESENSI (Superadmin Only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('presensi', [LaporanPresensiController::class, 'index'])->name('presensi.index');
        Route::get('presensi/export/pdf', [LaporanPresensiController::class, 'exportPdf'])->name('presensi.export.pdf');
        Route::get('presensi/export/excel', [LaporanPresensiController::class, 'exportExcel'])->name('presensi.export.excel');
    });
});







/*
|--------------------------------------------------------------------------
| Universal Redirect After Login (auth: /dashboard)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) return redirect('/login');

    return match ($user->role) {
        'superadmin' => redirect()->route('superadmin.dashboard'),
        'admin'      => redirect()->route('admin.dashboard'),
        default      => redirect()->route('user.dashboard'),
    };
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
