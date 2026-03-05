<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Histori Izin MNA</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #2d3748; }
        
        /* HEADER STYLE */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #006C68; padding-bottom: 12px; }
        .title { font-size: 20px; font-weight: 800; color: #004B49; margin: 0 0 5px 0; letter-spacing: 1px; }
        .subtitle { font-size: 14px; font-weight: bold; margin: 0 0 8px 0; color: #333; }
        .info { font-size: 10px; color: #475569; margin: 0; }
        
        /* TABLE STYLE */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { 
            background-color: #006C68; /* MNA Teal */
            color: #ffffff; 
            font-weight: bold; 
            text-transform: uppercase; 
            font-size: 10px; 
            padding: 10px 8px; 
            text-align: center; 
            border: 1px solid #004B49;
        }
        td { 
            border: 1px solid #cbd5e1; 
            padding: 8px; 
            vertical-align: top; 
        }
        tr:nth-child(even) { background-color: #f8fafc; } /* Efek belang-belang agar mudah dibaca */
        
        /* UTILITIES */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-red { color: #dc2626; font-weight: bold; }
        .text-green { color: #16a34a; font-weight: bold; }
        
        /* BADGE */
        .badge { padding: 3px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; display: inline-block;}
        .badge-tugas { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-pribadi { background-color: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    </style>
</head>
<body>
    
    <div class="header">
        <h1 class="title">PT MULTI NABATI ASAHAN (WILMAR GROUP)</h1>
        <h2 class="subtitle">LAPORAN HISTORI IZIN KELUAR KANTOR (IKK) KARYAWAN</h2>
        <p class="info">
            Departemen: <strong>{{ $departmentName }}</strong> &nbsp;|&nbsp; 
            Periode: <strong>{{ $month ? date('F Y', strtotime($month)) : 'Semua Periode' }}</strong> &nbsp;|&nbsp; 
            Dicetak pada: <strong>{{ date('d/m/Y H:i') }}</strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="15%">Karyawan</th>
                <th width="10%">Tgl Izin</th>
                <th width="8%">Jenis</th>
                <th width="20%">Keperluan</th>
                <th width="12%">Target (Out-In)</th>
                <th width="12%">Aktual (Out-In)</th>
                <th width="8%">Status</th>
                <th width="12%">Keterlambatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($permits as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $p->user->name }}</strong><br>
                    <span style="font-size: 9px; color: #64748b;">NIK: {{ $p->user->nik ?? '-' }}</span>
                </td>
                <td class="text-center"><strong>{{ $p->permit_date ? \Carbon\Carbon::parse($p->permit_date)->format('d/m/Y') : '-' }}</strong></td>
                <td class="text-center">
                    <span class="badge {{ $p->permit_type == 'tugas' ? 'badge-tugas' : 'badge-pribadi' }}">
                        {{ strtoupper($p->permit_type) }}
                    </span>
                </td>
                <td>{{ $p->reason }}</td>
                <td class="text-center" style="color: #475569; font-family: monospace; font-size: 10px;">
                    {{ $p->target_time_out ? \Carbon\Carbon::parse($p->target_time_out)->format('H:i') : '-' }} <br> s/d <br>
                    {{ $p->target_time_in ? \Carbon\Carbon::parse($p->target_time_in)->format('H:i') : '-' }}
                </td>
                <td class="text-center" style="font-family: monospace; font-weight: bold; font-size: 10px;">
                    {{ $p->time_out ? \Carbon\Carbon::parse($p->time_out)->format('H:i') : 'Belum Scan' }} <br> s/d <br>
                    {{ $p->time_in ? \Carbon\Carbon::parse($p->time_in)->format('H:i') : 'Belum Scan' }}
                </td>
                <td class="text-center">
                    @if($p->status == 'returned') Selesai
                    @elseif($p->status == 'out') Di Luar
                    @else - @endif
                </td>
                <td class="text-center">
                    @if($p->late_minutes > 0)
                        <span class="text-red">Telat {{ $p->late_minutes }} Menit</span>
                    @else
                        <span class="text-green">Tepat Waktu</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 30px; font-style: italic; color: #94a3b8;">
                    Tidak ada data histori ditemukan pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
</body>
</html>