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
        $permit = Permit::where('uuid', $request->uuid)->with('user')->first();

        if (!$permit) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak ditemukan!']);
        }

        // 2. Cek Status Approval
        if (!in_array($permit->status, ['approved', 'out'])) {
            if ($permit->status == 'returned') {
                 return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah selesai digunakan (Expired).']);
            }
            return response()->json(['status' => 'error', 'message' => 'Izin belum disetujui atau dibatalkan!']);
        }

        // 3. LOGIKA CEK KELUAR / MASUK
        $message = '';
        $type = '';
        $now = Carbon::now();

        // KASUS A: Belum Keluar -> Catat Jam Keluar
        if ($permit->time_out == null) {
            $permit->update([
                'status' => 'out',
                'time_out' => $now,
                'security_out_id' => Auth::id()
            ]);
            $type = 'OUT';
            $message = 'Berhasil! Karyawan KELUAR area pabrik.';
        } 
        // KASUS B: Sudah Keluar, Belum Masuk -> Catat Jam Masuk
        elseif ($permit->time_out != null && $permit->time_in == null) {
            $lateMinutes = null;
            $lateMessage = '';

            // LOGIKA HITUNG KETERLAMBATAN (Khusus Izin Pribadi)
            if ($permit->permit_type == 'pribadi' && $permit->target_time_in != null) {
                // Gabungkan tanggal hari ini dengan jam target kembali
                $targetDateTime = Carbon::parse($now->format('Y-m-d') . ' ' . $permit->target_time_in);
                
                // Jika waktu sekarang melebihi target waktu kembali
                if ($now->greaterThan($targetDateTime)) {
                    $lateMinutes = $now->diffInMinutes($targetDateTime); // Hitung selisih menit
                    $lateMessage = " (Terlambat {$lateMinutes} menit!)";
                }
            }

            $permit->update([
                'status' => 'returned', // Status selesai
                'time_in' => $now,
                'security_in_id' => Auth::id(),
                'late_minutes' => $lateMinutes // Simpan keterlambatan ke DB
            ]);
            
            $type = 'IN';
            $message = 'Berhasil! Karyawan KEMBALI ke area pabrik.' . $lateMessage;
        } 
        // KASUS C: Fallback Error
        else {
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan validasi data.']);
        }

        return response()->json([
            'status' => 'success', 
            'message' => $message, 
            'type' => $type,
            'user' => $permit->user->name ?? 'Karyawan',
            'time' => $now->format('H:i')
        ]);
    }
}