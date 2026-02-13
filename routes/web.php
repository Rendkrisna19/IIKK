<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. GUEST ROUTES (Belum Login) ---
Route::middleware('guest')->group(function () {
    // Redirect halaman awal ke login
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// --- 2. AUTHENTICATED ROUTES (Sudah Login) ---
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- DASHBOARD CONTROLLER (PENGATUR LALU LINTAS) ---
    // Route ini mengecek role user dan mengarahkan ke tampilan yang sesuai
    // --- DASHBOARD CONTROLLER (PENGATUR LALU LINTAS & DATA) ---
    Route::get('/dashboard', function () {
    $user = Auth::user();
    $role = $user->role;

    // 1. DASHBOARD ADMIN
    if ($role === 'admin') {
        // PERBAIKAN DISINI:
        // Jangan langsung return view, tapi panggil function dashboard() dari AdminController
        // agar variabel $stats, $chartData, dll terkirim ke view.
        return app(App\Http\Controllers\AdminController::class)->dashboard();
    } 
    
    // 2. DASHBOARD HOD
    elseif ($role === 'hod') {
        return app(App\Http\Controllers\HodController::class)->dashboard();
    } 
    
    // 3. DASHBOARD SECURITY
    elseif ($role === 'security') {
        return redirect()->route('security.dashboard');
    }

    // 4. DASHBOARD KARYAWAN (EMPLOYEE) - DATA REAL
    else {
        // Ambil 5 riwayat izin terakhir
        $recentPermits = \App\Models\Permit::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Hitung Statistik
        $stats = [
            'total' => \App\Models\Permit::where('user_id', $user->id)->count(),
            'pending' => \App\Models\Permit::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => \App\Models\Permit::where('user_id', $user->id)->where('status', 'approved')->count(),
            'rejected' => \App\Models\Permit::where('user_id', $user->id)->where('status', 'rejected')->count(),
        ];

        return view('employee.dashboard', compact('recentPermits', 'stats'));
    }

})->name('dashboard');


    // =================================================================
    // GROUP: SUPER ADMIN (HRD/MIS)
    // Akses: Manajemen User, Departemen, Laporan Full
    // =================================================================
    Route::prefix('admin')->name('admin.')->group(function () {
        // Nanti kita isi Controller-nya di sini
        Route::get('/users', function () {
            return "Manajemen Karyawan";
        })->name('users.index');
        Route::resource('departments', \App\Http\Controllers\DepartmentController::class);
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::get('/reports', [App\Http\Controllers\AdminController::class, 'reports'])->name('reports.index');
        Route::get('/reports/pdf', [App\Http\Controllers\AdminController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [App\Http\Controllers\AdminController::class, 'exportExcel'])->name('reports.excel');
    });


    // =================================================================
    // GROUP: HOD (Head of Department / Manager)
    // Akses: Approval Izin Bawahan
    // =================================================================
    Route::prefix('hod')->name('hod.')->group(function () {
        Route::get('/approvals', [App\Http\Controllers\HodController::class, 'approvals'])->name('approvals');
        Route::get('/history', [App\Http\Controllers\HodController::class, 'history'])->name('history');
        Route::patch('/permit/{permit}/update', [App\Http\Controllers\HodController::class, 'updateStatus'])->name('permit.update');
    });


    // =================================================================
    // GROUP: SECURITY
    // Akses: Input Jam Keluar/Masuk, Validasi Surat
    // =================================================================
    Route::prefix('security')->name('security.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\SecurityController::class, 'index'])->name('dashboard');
        Route::post('/scan', [App\Http\Controllers\SecurityController::class, 'scan'])->name('scan');
    });



    // =================================================================
    // GROUP: EMPLOYEE (Karyawan Biasa)
    // Akses: Buat Izin Baru, Lihat Status Izin Sendiri
    // =================================================================
    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/permit/create', [App\Http\Controllers\PermitController::class, 'create'])->name('permit.create');
        Route::post('/permit', [App\Http\Controllers\PermitController::class, 'store'])->name('permit.store');
        Route::get('/permit/{permit}/print', [App\Http\Controllers\PermitController::class, 'print'])->name('permit.print');
        Route::get('/my-permits', [App\Http\Controllers\PermitController::class, 'index'])->name('my-permits');
    });
});
