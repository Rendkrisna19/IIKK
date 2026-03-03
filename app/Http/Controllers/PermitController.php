<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class PermitController extends Controller
{
    /**
     * Menampilkan Riwayat Izin Saya (History)
     */
    public function index()
    {
        $permits = Permit::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);

        // Path folder sekarang: employee/permits
        return view('employee.permits.index', compact('permits'));
    }

    /**
     * Menampilkan Form Buat Izin Baru
     */
    public function create()
    {
        // Path folder sekarang: employee/permits
        return view('employee.permits.create');
    }

    /**
     * Proses Simpan Data Izin ke Database
     */
   public function store(Request $request)
    {
        $request->validate([
            'permit_date' => 'required|date', // Tambahkan validasi tanggal
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
        Permit::create([
            'uuid' => (string) Str::uuid(),
            'unique_code' => $uniqueCode,
            'user_id' => $user->id,
            'permit_date' => $request->permit_date, // Menyimpan tanggal yang dipilih
            'permit_type' => $request->permit_type,
            'reason' => $request->reason,
            'target_time_out' => $request->target_time_out,
            'target_time_in' => $request->permit_type == 'pribadi' ? $request->target_time_in : null,
            'status' => 'pending',
        ]);
        return redirect()->route('employee.my-permits')
            ->with('success', 'Permohonan berhasil dikirim!');
    }

    /**
     * Cetak Surat Izin (PDF) dengan QR Code
     */
    public function print(Permit $permit)
    {
        // 1. Validasi Keamanan
        if($permit->user_id != auth()->id() || $permit->status != 'approved') {
            return abort(403, 'Anda tidak memiliki akses mencetak dokumen ini.');
        }

        // 2. SELF-HEALING UUID (jika data lama kosong)
        if (empty($permit->uuid)) {
            $permit->uuid = (string) Str::uuid();
            $permit->save();
            $permit->refresh(); 
        }

        $qrContent = (string) $permit->uuid;

        if (empty($qrContent)) {
            return back()->with('error', 'Gagal generate QR Code: UUID tidak valid.');
        }

        // 3. Generate QR Code
        try {
            $qrcode = base64_encode(QrCode::format('svg')->size(100)->generate($qrContent));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat QR Code.');
        }

        // 4. Generate & Stream PDF
        // Path folder sekarang: employee/permits
        $pdf = Pdf::loadView('employee.permits.pdf', compact('permit', 'qrcode'));
        $pdf->setPaper('A4', 'portrait');
        
        $fileName = 'IKK_' . ($permit->user->nik ?? 'NONIK') . '_' . date('Ymd') . '.pdf';
        
        return $pdf->stream($fileName);
    }

    /**
     * Menampilkan halaman form edit
     */
   /**
     * Menampilkan halaman form edit
     */
    public function edit($id)
    {
        $permit = Permit::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Menggunakan folder 'permits' (plural) sesuai dengan nama foldermu
        return view('employee.permits.edit', compact('permit'));
    }

    /**
     * Memproses penyimpanan update data ke database
     */
    public function update(Request $request, $id)
    {
        // 1. Cari data izin yang masih pending milik user tersebut
        $permit = Permit::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        // 2. Validasi input (TANGGAL SUDAH DITAMBAHKAN DI SINI)
        $request->validate([
            'permit_date'     => 'required|date',
            'permit_type'     => 'required|in:tugas,pribadi',
            'reason'          => 'required|string|max:255',
            'target_time_out' => 'required',
            'target_time_in'  => 'required_if:permit_type,pribadi', 
        ]);

        // 3. Update datanya ke database
        $permit->update([
            'permit_date'     => $request->permit_date, // Menyimpan perubahan tanggal
            'permit_type'     => $request->permit_type,
            'reason'          => $request->reason,
            'target_time_out' => $request->target_time_out,
            'target_time_in'  => $request->permit_type == 'pribadi' ? $request->target_time_in : null,
        ]);

        // 4. Redirect kembali ke halaman riwayat (my-permits)
        return redirect()->route('employee.my-permits')
            ->with('success', 'Pengajuan izin berhasil diperbarui.');
    }
    /**
     * Memproses penghapusan / pembatalan izin
     */
    public function destroy($id)
    {
        $permit = Permit::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();

        $permit->delete();

        return redirect()->route('employee.my-permits')
            ->with('success', 'Pengajuan izin berhasil dibatalkan dan dihapus.');
    }
}