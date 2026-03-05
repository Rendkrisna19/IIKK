<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SecurityController extends Controller
{
    public function index()
    {
        $todayLogs = Permit::whereDate('time_out', Carbon::today())
                           ->orWhereDate('time_in', Carbon::today())
                           ->with(['user.department', 'approver'])
                           ->latest('updated_at')->take(10)->get();

        $stats = [
            'out' => Permit::whereDate('time_out', Carbon::today())->count(),
            'in'  => Permit::whereDate('time_in', Carbon::today())->count(),
            'active_outside' => Permit::whereDate('time_out', Carbon::today())->whereNull('time_in')->count(),
        ];

        return view('security.dashboard', compact('todayLogs', 'stats'));
    }

    // TAHAP 1: BACA DATA & VALIDASI (TIDAK SIMPAN KE DATABASE DULU)
    public function scan(Request $request)
    {
        $request->validate(['uuid' => 'required']);

        $permit = Permit::where('uuid', $request->uuid)->with('user.department')->first();

        if (!$permit) {
            return response()->json(['status' => 'error', 'message' => 'QR Code tidak valid / tidak ditemukan!']);
        }

        $now = Carbon::now();

        // Validasi Status Dasar
        if ($permit->status == 'pending') return response()->json(['status' => 'error', 'message' => 'Izin belum disetujui HOD!']);
        if ($permit->status == 'rejected') return response()->json(['status' => 'error', 'message' => 'Izin ini telah ditolak HOD.']);
        if ($permit->status == 'returned') return response()->json(['status' => 'error', 'message' => 'Tiket ini sudah Expired / Selesai digunakan.']);
        if (!in_array($permit->status, ['approved', 'out'])) return response()->json(['status' => 'error', 'message' => 'Status tidak valid.']);

        $action = '';

        // JIKA KARYAWAN MAU KELUAR
        if ($permit->status == 'approved' && $permit->time_out == null) {
            $targetTimeOut = Carbon::parse($permit->permit_date . ' ' . $permit->target_time_out);
            $earliestAllowedTime = $targetTimeOut->copy()->subMinutes(15);

            // Validasi Jam Keluar (Misal belum waktunya)
            if ($now->lessThan($earliestAllowedTime)) {
                $diff = $now->diff($targetTimeOut);
                $countdownMsg = ($diff->h > 0 ? $diff->h . ' Jam ' : '') . $diff->i . ' Menit';
                return response()->json([
                    'status' => 'warning',
                    'message' => "Belum waktunya keluar! Jadwal keluar pukul {$targetTimeOut->format('H:i')}. Silakan tunggu $countdownMsg lagi."
                ]);
            }
            $action = 'OUT';
        } 
        // JIKA KARYAWAN MAU MASUK
        elseif ($permit->status == 'out' && $permit->time_in == null) {
            $action = 'IN';
        }

        // KEMBALIKAN DATA KE LAYAR HP SECURITY UNTUK DIVERIFIKASI
        return response()->json([
            'status' => 'verify',
            'action' => $action,
            'uuid' => $permit->uuid,
            'user' => [
                'name' => $permit->user->name,
                'nik' => $permit->user->nik,
                'department' => $permit->user->department->name ?? '-',
                'photo' => $permit->user->profile_photo ? asset('storage/' . $permit->user->profile_photo) : null,
                'initials' => substr($permit->user->name, 0, 1)
            ],
            'permit' => [
                'reason' => $permit->reason,
                'type' => $permit->permit_type,
                'target_time_in' => $permit->target_time_in ? Carbon::parse($permit->target_time_in)->format('H:i') : '-'
            ]
        ]);
    }

    // TAHAP 2: EKSEKUSI SETELAH SECURITY KLIK "SESUAI & IZINKAN" / "BEDA ORANG"
    public function confirm(Request $request)
    {
        $request->validate([
            'uuid' => 'required',
            'action' => 'required|in:OUT,IN',
            'is_verified' => 'required|boolean'
        ]);

        // JIKA SECURITY KLIK "TOLAK / BEDA ORANG"
        if (!$request->is_verified) {
            return response()->json(['status' => 'error', 'message' => 'Dibatalkan: Wajah/Identitas fisik tidak sesuai dengan data sistem!']);
        }

        $permit = Permit::where('uuid', $request->uuid)->first();
        if (!$permit) return response()->json(['status' => 'error', 'message' => 'Data izin tidak valid.']);

        $now = Carbon::now();

        // EKSEKUSI KELUAR
        if ($request->action == 'OUT') {
            $permit->update([
                'status' => 'out',
                'time_out' => $now,
                'security_out_id' => Auth::id()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Berhasil! Karyawan telah diverifikasi KELUAR area pabrik.']);
        } 
        
        // EKSEKUSI MASUK
        if ($request->action == 'IN') {
            $lateMinutes = null;
            
            // Cek Keterlambatan Izin Pribadi
            if ($permit->permit_type == 'pribadi' && $permit->target_time_in != null) {
                $targetTimeIn = Carbon::parse($permit->permit_date . ' ' . $permit->target_time_in);
                if ($now->greaterThan($targetTimeIn)) {
                    $lateMinutes = $now->diffInMinutes($targetTimeIn);
                }
            }
            
            $permit->update([
                'status' => 'returned',
                'time_in' => $now,
                'security_in_id' => Auth::id(),
                'late_minutes' => $lateMinutes
            ]);
            
            $msg = 'Berhasil! Karyawan telah diverifikasi MASUK.';
            if ($lateMinutes) $msg .= " (Terlambat $lateMinutes menit!)";
            
            return response()->json(['status' => 'success', 'message' => $msg]);
        }
    }
}