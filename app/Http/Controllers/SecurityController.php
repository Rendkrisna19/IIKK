<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SecurityController extends Controller
{
    // Halaman Utama (Dashboard Mobile)
    public function index()
    {
        // Ambil Log Hari Ini (Yang sudah discan keluar/masuk hari ini)
        $todayLogs = Permit::whereDate('time_out', Carbon::today())
                           ->orWhereDate('time_in', Carbon::today())
                           ->with(['user.department', 'approver'])
                           ->latest('updated_at')
                           ->take(10)
                           ->get();

        // Hitung Statistik Hari Ini
        $stats = [
            'out' => Permit::whereDate('time_out', Carbon::today())->count(),
            'in'  => Permit::whereDate('time_in', Carbon::today())->count(),
            'active_outside' => Permit::whereDate('time_out', Carbon::today())
                                      ->whereNull('time_in')
                                      ->count(),
        ];

        return view('security.dashboard', compact('todayLogs', 'stats'));
    }

    // Proses Scan QR Code (Ajax Request)
    public function scan(Request $request)
    {
        $request->validate(['uuid' => 'required']);

        // 1. Cari Permit Berdasarkan UUID
        $permit = Permit::where('uuid', $request->uuid)->first();

        if (!$permit) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak ditemukan!']);
        }

        // 2. Cek Status Approval
        if ($permit->status != 'approved' && $permit->status != 'out') {
            return response()->json(['status' => 'error', 'message' => 'Izin belum disetujui atau sudah selesai!']);
        }

        // 3. LOGIKA CEK KELUAR / MASUK
        $message = '';
        $type = '';

        // KASUS A: Belum Keluar -> Catat Jam Keluar
        if ($permit->time_out == null) {
            $permit->update([
                'status' => 'out',
                'time_out' => now(),
                'security_out_id' => Auth::id()
            ]);
            $type = 'OUT';
            $message = 'Berhasil! Karyawan KELUAR area pabrik.';
        } 
        // KASUS B: Sudah Keluar, Belum Masuk -> Catat Jam Masuk
        elseif ($permit->time_out != null && $permit->time_in == null) {
            $permit->update([
                'status' => 'returned', // Status selesai
                'time_in' => now(),
                'security_in_id' => Auth::id()
            ]);
            $type = 'IN';
            $message = 'Berhasil! Karyawan KEMBALI ke area pabrik.';
        } 
        // KASUS C: Sudah Selesai
        else {
            return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah digunakan (Expired).']);
        }

        return response()->json([
            'status' => 'success', 
            'message' => $message, 
            'type' => $type,
            'user' => $permit->user->name,
            'time' => now()->format('H:i')
        ]);
    }
}