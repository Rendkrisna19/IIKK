<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permit;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PermitExport;

class AdminController extends Controller
{
    // === DASHBOARD & CHARTS ===
   // AdminController.php

    public function dashboard()
    {
        // 1. Statistik Utama
        $stats = [
            'total_users' => User::count(),
            'total_permits_today' => Permit::whereDate('created_at', Carbon::today())->count(),
            'total_permits_month' => Permit::whereMonth('created_at', Carbon::now()->month)->count(),
            'active_out' => Permit::whereNotNull('time_out')->whereNull('time_in')->count(),
        ];

        // 2. Data Chart: Izin per Departemen (PERBAIKAN DISINI)
        // Kita harus JOIN tabel 'users' untuk mendapatkan 'department_id'
        $deptStats = Permit::join('users', 'permits.user_id', '=', 'users.id')
            ->selectRaw('users.department_id, count(permits.id) as total')
            ->groupBy('users.department_id')
            ->get();
            
        $chartLabels = [];
        $chartData = [];
        
        foreach($deptStats as $stat) {
            // Ambil Nama Departemen berdasarkan ID
            $deptName = Department::find($stat->department_id)->name ?? 'Unknown';
            $chartLabels[] = $deptName;
            $chartData[] = $stat->total;
        }

        // 3. Data Chart: Status Izin
        $statusStats = [
            Permit::where('status', 'approved')->count(),
            Permit::where('status', 'rejected')->count(),
            Permit::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartData', 'statusStats'));
    }

    // === FITUR LAPORAN ===
    public function reports(Request $request)
    {
        $departments = Department::all();
        $permits = [];

        // Jika ada filter yang dikirim
        if ($request->has('start_date')) {
            $query = Permit::with(['user.department', 'approver'])
                ->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);

            if ($request->department_id) {
                $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
            }
            
            if ($request->status) {
                $query->where('status', $request->status);
            }

            $permits = $query->latest()->get();
        }

        return view('admin.reports.index', compact('departments', 'permits'));
    }

    public function exportPdf(Request $request)
    {
        // Copy logic filter yg sama
        $query = Permit::with(['user.department', 'approver'])
            ->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);

        if ($request->department_id) $query->whereHas('user', fn($q) => $q->where('department_id', $request->department_id));
        if ($request->status) $query->where('status', $request->status);

        $permits = $query->get();

        $pdf = Pdf::loadView('admin.reports.pdf', compact('permits', 'request'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Laporan-Izin.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PermitExport(
            $request->start_date, 
            $request->end_date, 
            $request->department_id, 
            $request->status
        ), 'Laporan-Izin-Wilmar.xlsx');
    }
}