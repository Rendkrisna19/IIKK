<!DOCTYPE html>
<html>
<head>
    <title>Laporan Izin Keluar Kantor</title>
    <style>
        /* Reset & Base */
        @page { margin: 20px 30px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #333; line-height: 1.2; }
        
        /* HEADER / KOP SURAT */
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 25px; }
        .logo-img { width: 150px; height: auto;  }
        .company-name { font-size: 16pt; font-weight: bold; margin-bottom: 5px; color: #000; }
        .company-address { font-size: 9pt; color: #555; }

        /* JUDUL LAPORAN */
        .title-wrapper { text-align: center; margin-bottom: 20px; }
        .report-title { font-size: 14pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; margin-bottom: 5px; }
        .report-period { font-size: 10pt; font-style: italic; }

        /* TABEL DATA */
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #444; padding: 6px 4px; vertical-align: middle; }
        .table th { 
            background-color: #004B49; /* Warna Wilmar */
            color: #ffffff; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 8pt; 
            text-align: center; 
            height: 30px;
        }
        .table td { font-size: 9pt; }
        .row-even { background-color: #f9f9f9; }
        
        /* STATUS TEXT */
        .status-approved { color: #006C68; font-weight: bold; text-transform: uppercase; font-size: 8pt; }
        .status-rejected { color: #dc2626; font-weight: bold; text-transform: uppercase; font-size: 8pt; }
        .status-pending { color: #d97706; font-weight: bold; text-transform: uppercase; font-size: 8pt; }
        
        /* TANDA TANGAN */
        .signature-wrapper { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .signature-table { width: 100%; border: none; }
        .signature-table td { border: none; text-align: center; vertical-align: top; }
        .sign-space { height: 70px; }
        .sign-name { font-weight: bold; text-decoration: underline; font-size: 10pt; }
        .sign-role { font-size: 9pt; color: #555; }

        /* UTILS */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        
        /* FOOTER */
        .footer { 
            position: fixed; bottom: 0; left: 0; right: 0; 
            font-size: 8pt; color: #999; border-top: 1px solid #ddd; 
            padding-top: 5px; text-align: right; font-style: italic; 
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/mna-logo.png'); // Pastikan file ada di public/images/
        $logoData = '';
        
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <table class="header-table">
        <tr>
            <td width="15%" style="vertical-align: top;">
                @if(!empty($logoData))
                    <img src="{{ $logoData }}" class="logo-img">
                @else
                    <h2 style="color: #004B49; margin:0;">wilmar</h2>
                @endif
            </td>
            <td width="85%" class="text-center">
                <div class="company-name">PT. MULTI NABATI ASAHAN</div>
                <div class="company-address">Jl. Access Road Kuala Tanjung, Medang Deras, Kab. Batu Bara, Sumatera Utara</div>
                <div class="company-address">Telp: (0622) 123456 | Email: hr@wilmar.co.id | Web: www.wilmar-international.com</div>
            </td>
        </tr>
    </table>

    <div class="title-wrapper">
        <div class="report-title">Laporan Rekapitulasi Izin Keluar (IKK)</div>
        <div class="report-period">
            Periode: 
            <strong>{{ \Carbon\Carbon::parse($request->start_date)->isoFormat('D MMMM Y') }}</strong> 
            s.d. 
            <strong>{{ \Carbon\Carbon::parse($request->end_date)->isoFormat('D MMMM Y') }}</strong>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Tanggal</th>
                <th width="18%">Karyawan</th>
                <th width="12%">Dept.</th>
                <th width="8%">Tipe</th>
                <th width="22%">Keperluan</th>
                <th width="9%">Status</th>
                <th width="8%">Out</th>
                <th width="8%">In</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permits as $index => $permit)
            <tr class="{{ $index % 2 == 0 ? 'row-even' : '' }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $permit->created_at->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $permit->user->name }}</strong><br>
                    <span style="font-size: 8pt; color: #666;">NIK: {{ $permit->user->nik }}</span>
                </td>
                <td class="text-center">{{ $permit->user->department->name ?? '-' }}</td>
                <td class="text-center" style="text-transform: uppercase; font-size: 8pt;">
                    {{ $permit->permit_type }}
                </td>
                <td>{{ Str::limit($permit->reason, 100) }}</td>
                <td class="text-center">
                    @if($permit->status == 'approved') 
                        <span class="status-approved">Disetujui</span>
                    @elseif($permit->status == 'rejected')
                        <span class="status-rejected">Ditolak</span>
                    @elseif($permit->status == 'out')
                        <span class="status-pending">Keluar</span>
                    @elseif($permit->status == 'returned')
                        <span style="color:blue; font-weight:bold; font-size:8pt;">SELESAI</span>
                    @else
                        {{ ucfirst($permit->status) }}
                    @endif
                </td>
                <td class="text-center font-mono">{{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '-' }}</td>
                <td class="text-center font-mono">{{ $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 30px; font-style: italic;">
                    Tidak ada data ditemukan untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-bottom: 20px; font-size: 9pt; border: 1px dashed #999; padding: 10px; width: 40%;">
        <table style="width: 100%; border: none;">
            <tr><td style="border:none;">Total Data</td><td style="border:none;">: <strong>{{ count($permits) }}</strong> Record</td></tr>
            <tr><td style="border:none;">Dicetak Oleh</td><td style="border:none;">: {{ Auth::user()->name }}</td></tr>
            <tr><td style="border:none;">Waktu Cetak</td><td style="border:none;">: {{ now()->format('d F Y H:i') }} WIB</td></tr>
        </table>
    </div>

    <div class="signature-wrapper">
        <table class="signature-table">
            <tr>
                <td width="33%">
                    Dibuat Oleh,<br>
                    Admin HR/GA
                    <div class="sign-space"></div>
                    <span class="sign-name">{{ Auth::user()->name }}</span><br>
                    <span class="sign-role">Administrator</span>
                </td>
                <td width="33%"></td>
                <td width="33%">
                    Diketahui Oleh,<br>
                    Pimpinan Departemen
                    <div class="sign-space"></div>
                    <span class="sign-name">( ............................ )</span><br>
                    <span class="sign-role">Manager / HOD</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated by E-IKK System PT. MNA | Hal. <script type="text/php">if (isset($pdf)) { echo $pdf->get_page_number(); }</script>
    </div>

</body>
</html>