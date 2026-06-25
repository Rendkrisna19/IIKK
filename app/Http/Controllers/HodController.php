<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermitHistoryExport;
use Illuminate\Support\Str;

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

    public function create()
    {
        return view('hod.permits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permit_date' => 'required|date',
            'permit_type' => 'required|in:tugas,pribadi',
            'reason' => 'required|string|max:255',
            'target_time_out' => 'required',
            'target_time_in' => 'required_if:permit_type,pribadi', 
        ]);
        
        $user = Auth::user();
        $todayCount = Permit::whereDate('created_at', now()->toDateString())->count() + 1;
        $sequence = str_pad($todayCount, 3, '0', STR_PAD_LEFT); 
        $dateStr = now()->format('dmY'); 
        $userRequestCount = Permit::where('user_id', $user->id)->count() + 1; 
        
        $deptName = $user->department->name ?? 'NA';
        $words = explode(' ', $deptName);
        $deptAcronym = '';
        foreach ($words as $w) { $deptAcronym .= strtoupper($w[0]); }
        if(strlen($deptAcronym) < 2) {
            $deptAcronym = strtoupper(substr($deptName, 0, 3)); 
        }
        $uniqueCode = "{$sequence}/{$dateStr}/{$userRequestCount}/{$deptAcronym}";
        
        $typeId = \App\Models\PermitType::where('name', $request->permit_type)->value('id') ?? 1;
        $permit = Permit::create([
            'uuid' => (string) Str::uuid(),
            'unique_code' => $uniqueCode,
            'user_id' => $user->id,
            'permit_date' => $request->permit_date,
            'permit_type_id' => $typeId,
            'reason' => $request->reason,
            'target_time_out' => $request->target_time_out,
            'target_time_in' => $request->permit_type == 'pribadi' ? $request->target_time_in : null,
            'status' => 'approved',
        ]);
        
        \App\Models\PermitApproval::create([
            'permit_id' => $permit->id,
            'approver_id' => $user->id,
            'status' => 'approved',
            'hod_message' => null,
            'approved_at' => now(),
        ]);
        
        return redirect()->route('hod.my-tickets')
            ->with('success', 'Izin pribadi Anda berhasil dibuat dan langsung disetujui.');
    }

    public function myTickets()
    {
        $permits = Permit::where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('hod.permits.tickets', compact('permits'));
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

  public function history(Request $request)
    {
        $deptId = Auth::user()->department_id;
        
        $query = Permit::with(['user', 'approver'])
            ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
            ->whereIn('status', ['approved', 'out', 'returned']);
            
        // Jika ada request month, gunakan itu. Jika tidak, gunakan bulan ini.
        $filterMonth = $request->has('month') && $request->month != '' ? $request->month : date('Y-m');

        $query->whereMonth('permit_date', date('m', strtotime($filterMonth)))
              ->whereYear('permit_date', date('Y', strtotime($filterMonth)));

        // UBAH paginate() MENJADI get() untuk DataTables
        $permits = $query->orderBy('permit_date', 'desc')->orderBy('created_at', 'desc')->get();

        // Kirim kembali $filterMonth ke view agar input date terisi benar
        return view('hod.history', compact('permits', 'filterMonth'));
    }
    public function exportExcel(Request $request)
    {
        $deptId = Auth::user()->department_id;
        $month = $request->month; // Tangkap filter bulan

        return Excel::download(new PermitHistoryExport($deptId, $month), 'Laporan_IKK_MNA.xlsx');
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        $deptId = Auth::user()->department_id;
        $month = $request->month; // Tangkap filter bulan
        $departmentName = Auth::user()->department->name ?? '-';

        $query = Permit::with('user')
            ->whereHas('user', fn($q) => $q->where('department_id', $deptId))
            ->whereIn('status', ['approved', 'out', 'returned']);

        // Terapkan filter jika ada
        if ($month) {
            $query->whereMonth('permit_date', date('m', strtotime($month)))
                  ->whereYear('permit_date', date('Y', strtotime($month)));
        }

        $permits = $query->orderBy('permit_date', 'desc')->get();

        // Load View PDF
        $pdf = Pdf::loadView('hod.exports.pdf', compact('permits', 'month', 'departmentName'))
                  ->setPaper('A4', 'landscape');
                  
        return $pdf->download('Laporan_IKK_MNA.pdf');
    }
    /**
     * Proses Approve atau Reject Izin
     */
   public function updateStatus(Request $request, Permit $permit)
    {
        // 1. Validasi Input (Tambahkan validasi untuk hod_message)
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'hod_message' => 'nullable|string|max:500' // Maksimal 500 karakter biar aman
        ]);

        // 2. Keamanan: Pastikan HOD hanya mengurus karyawan departemennya sendiri
        if($permit->user->department_id != Auth::user()->department_id) {
            return abort(403, 'Akses Ditolak. Karyawan beda departemen.');
        }

        // 3. Update Data (Tambahkan hod_message)
        $permit->update([
            'status' => $request->status,
        ]);
        
        \App\Models\PermitApproval::create([
            'permit_id' => $permit->id,
            'approver_id' => Auth::id(),
            'status' => $request->status,
            'hod_message' => $request->hod_message,
            'approved_at' => now(),
        ]);

        // 4. Pesan Feedback
        $message = $request->status == 'approved' 
            ? 'Izin disetujui. QR Code telah diterbitkan untuk karyawan.' 
            : 'Permohonan izin ditolak. Pesan telah dikirim ke karyawan.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Proses Pembatalan Izin oleh HOD
     */
    public function cancel(Request $request, Permit $permit)
    {
        $request->validate([
            'cancel_message' => 'required|string|max:500'
        ]);

        if($permit->user->department_id != Auth::user()->department_id) {
            return abort(403, 'Akses Ditolak. Karyawan beda departemen.');
        }

        if ($permit->status != 'approved') {
            return back()->with('error', 'Hanya pengajuan yang telah disetujui yang dapat dibatalkan.');
        }

        $permit->update([
            'status' => 'cancelled',
            'cancel_message' => $request->cancel_message,
        ]);

        return redirect()->back()->with('success', 'Pengajuan izin berhasil dibatalkan.');
    }
}