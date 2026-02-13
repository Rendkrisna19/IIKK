<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HodController extends Controller
{
    public function dashboard()
    {
        $deptId = Auth::user()->department_id;

        // 1. Hitung Statistik (Hanya data departemen dia sendiri)
        $stats = [
            'pending' => Permit::whereHas('user', fn($q) => $q->where('department_id', $deptId))
                                ->where('status', 'pending')->count(),
                                
            'today_out' => Permit::whereHas('user', fn($q) => $q->where('department_id', $deptId))
                                ->whereDate('created_at', today())
                                ->where('status', 'approved')->count(),

            'total_month' => Permit::whereHas('user', fn($q) => $q->where('department_id', $deptId))
                                ->whereMonth('created_at', now()->month)
                                ->count(),
        ];

        // 2. Ambil 5 Request Terbaru (Pending) untuk Quick Action
        $pendingPermits = Permit::with('user')
                                ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
                                ->where('status', 'pending')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('hod.dashboard', compact('stats', 'pendingPermits'));
    }

    public function approvals()
    {
        // Menampilkan semua yang pending
        $deptId = Auth::user()->department_id;
        $permits = Permit::with('user')
                        ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
                        ->where('status', 'pending')
                        ->latest()
                        ->get();
                        
        return view('hod.approvals', compact('permits'));
    }

    public function history()
    {
        // Menampilkan riwayat (Approved/Rejected)
        $deptId = Auth::user()->department_id;
        $permits = Permit::with(['user', 'approver'])
                        ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
                        ->whereIn('status', ['approved', 'rejected', 'out', 'returned']) // Status selain pending
                        ->latest()
                        ->paginate(10);

        return view('hod.history', compact('permits'));
    }

    /**
     * Proses Approve atau Reject Izin
     */
    public function updateStatus(Request $request, Permit $permit)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        // 2. Keamanan: Pastikan HOD hanya mengurus karyawan departemennya sendiri
        if($permit->user->department_id != Auth::user()->department_id) {
            return abort(403, 'Akses Ditolak. Karyawan beda departemen.');
        }

        // 3. Update Data
        $permit->update([
            'status' => $request->status,
            'approved_by' => Auth::id(), // ID HOD yang login
            'approved_at' => now(),      // Waktu saat ini
        ]);

        // 4. Pesan Feedback
        $message = $request->status == 'approved' 
            ? 'Izin disetujui. QR Code telah diterbitkan untuk karyawan.' 
            : 'Permohonan izin ditolak.';

        return redirect()->back()->with('success', $message);
    }
}