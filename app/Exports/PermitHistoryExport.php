<?php

namespace App\Exports;

use App\Models\Permit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermitHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithCustomStartCell
{
    protected $deptId;
    protected $month;

    public function __construct($deptId, $month = null)
    {
        $this->deptId = $deptId;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Permit::with('user')
            ->whereHas('user', fn($q) => $q->where('department_id', $this->deptId))
            ->whereIn('status', ['approved', 'out', 'returned']);

        // Terapkan filter bulan
        if ($this->month) {
            $query->whereMonth('permit_date', date('m', strtotime($this->month)))
                  ->whereYear('permit_date', date('Y', strtotime($this->month)));
        }

        return $query->latest('permit_date')->get();
    }

    // Mulai tabel dari baris ke-5, baris 1-4 untuk Judul
    public function startCell(): string
    {
        return 'A5';
    }

    public function headings(): array
    {
        return ['Nama Karyawan', 'NIK', 'Tanggal Izin', 'Jenis', 'Keperluan', 'Target Keluar', 'Target Kembali', 'Jam Aktual Keluar', 'Jam Aktual Masuk', 'Status', 'Keterlambatan'];
    }

   public function map($permit): array
    {
        return [
            $permit->user->name ?? '-',
            
            // FIX NIK: Tambahkan tanda petik tunggal (') di depan NIK
            $permit->user->nik ? "'" . $permit->user->nik : '-',
            
            $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('d-m-Y') : '-',
            strtoupper($permit->permit_type),
            $permit->reason,
            $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '-',
            $permit->target_time_in ? \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') : '-',
            $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : 'Belum Scan',
            $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : 'Belum Scan',
            strtoupper($permit->status),
            $permit->late_minutes > 0 ? $permit->late_minutes . ' Menit' : 'Tepat Waktu'
        ];
    }

    // Style Header Tabel (Warna Hijau Wilmar)
    public function styles(Worksheet $sheet)
    {
        return [
            5 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']], 
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => '006C68']], // Hex Hijau MNA
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
            ],
        ];
    }

    // Menambahkan Judul Kop Perusahaan & Garis Tabel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Merge cell untuk judul
                $sheet->mergeCells('A1:K1');
                $sheet->mergeCells('A2:K2');
                $sheet->mergeCells('A3:K3');

                // Isi Text Kop Perusahaan
                $sheet->setCellValue('A1', 'PT MULTI NABATI ASAHAN (WILMAR GROUP)');
                $sheet->setCellValue('A2', 'LAPORAN HISTORI IZIN KELUAR KANTOR (IKK)');
                
                // Keterangan Periode
                $periode = $this->month ? date('F Y', strtotime($this->month)) : 'Semua Periode';
                $sheet->setCellValue('A3', 'Periode: ' . $periode);

                // Styling Judul
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A2')->getFont()->getColor()->setArgb('004B49');
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Beri garis border untuk seluruh data tabel
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:K' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
                
                // Center alignment untuk kolom jam dan status
                $sheet->getStyle('C6:D'.$highestRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('F6:K'.$highestRow)->getAlignment()->setHorizontal('center');
            },
        ];
    }
}