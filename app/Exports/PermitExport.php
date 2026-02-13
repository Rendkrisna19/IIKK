<?php

namespace App\Exports;

use App\Models\Permit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate, $endDate, $deptId, $status;

    public function __construct($startDate, $endDate, $deptId, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->deptId = $deptId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Permit::with(['user.department', 'approver'])
            ->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59']);

        if ($this->deptId) {
            $query->whereHas('user', fn($q) => $q->where('department_id', $this->deptId));
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }

    // Mapping Data agar Rapi
    public function map($permit): array
    {
        return [
            $permit->created_at->format('d/m/Y'),
            $permit->user->nik ?? '-',
            $permit->user->name,
            $permit->user->department->name ?? '-',
            ucfirst($permit->permit_type),
            $permit->reason,
            ucfirst($permit->status),
            $permit->approver->name ?? '-',
            $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '-',
            $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'NIK', 'Nama Karyawan', 'Departemen', 
            'Tipe Izin', 'Alasan', 'Status', 'Disetujui Oleh', 'Jam Keluar', 'Jam Masuk'
        ];
    }

    // Styling Header agar Tebal & Berwarna
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF004B49']]], // Header Hijau Wilmar
        ];
    }
}