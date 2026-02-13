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

        return view('employee.permits.index', compact('permits'));
    }

    /**
     * Menampilkan Form Buat Izin Baru
     */
    public function create()
    {
        return view('employee.permits.create');
    }

    /**
     * Proses Simpan Data Izin ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'permit_type' => 'required|in:tugas,pribadi',
            'reason' => 'required|string|max:255',
        ], [
            'permit_type.required' => 'Wajib memilih jenis keperluan (Tugas/Pribadi).',
            'reason.required' => 'Alasan izin harus diisi detail.',
        ]);

        // Create dengan UUID otomatis (dihandle oleh Model atau fallback disini)
        Permit::create([
            'uuid' => (string) Str::uuid(), // KITA PAKSA ISI DISINI BIAR AMAN
            'user_id' => Auth::id(),
            'permit_type' => $request->permit_type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.my-permits')
            ->with('success', 'Permohonan berhasil dikirim! Menunggu persetujuan Atasan/HOD.');
    }

    /**
     * Cetak Surat Izin (PDF) dengan QR Code
     */
    public function print(Permit $permit)
    {
        // 1. Validasi Keamanan: Hanya milik sendiri & Status Approved
        if($permit->user_id != auth()->id() || $permit->status != 'approved') {
            return abort(403, 'Anda tidak memiliki akses mencetak dokumen ini.');
        }

        // 2. SELF-HEALING: Jika UUID Kosong (Data Lama), Isi Otomatis
        if (empty($permit->uuid)) {
            $permit->uuid = (string) Str::uuid();
            $permit->save();
            $permit->refresh(); // Refresh data model
        }

        // 3. Pastikan UUID benar-benar string sebelum masuk QR Code
        $qrContent = (string) $permit->uuid;

        if (empty($qrContent)) {
            return back()->with('error', 'Gagal generate QR Code: UUID tidak valid.');
        }

        // 4. Generate QR Code
        try {
            $qrcode = base64_encode(QrCode::format('svg')->size(100)->generate($qrContent));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat QR Code.');
        }

        // 5. Generate PDF
        $pdf = Pdf::loadView('employee.permits.pdf', compact('permit', 'qrcode'));
        $pdf->setPaper('A4', 'portrait');
        
        // Nama file PDF: IKK_NIK_TANGGAL.pdf
        $fileName = 'IKK_' . ($permit->user->nik ?? 'NONIK') . '_' . date('Ymd') . '.pdf';
        
        return $pdf->stream($fileName);
    }
}