<!DOCTYPE html>
<html>
<head>
    <title>Surat Izin Keluar Masuk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #000; }
        .container { width: 100%; padding: 10px; }
        
        /* HEADER */
        .header-table { width: 100%; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .logo { width: 120px; }
        .title { text-align: center; font-weight: bold; font-size: 16px; text-transform: uppercase; }
        .doc-no { text-align: right; font-size: 10px; }

        /* DATA KARYAWAN */
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 4px; vertical-align: top; }
        .label { width: 120px; font-weight: bold; }
        .colon { width: 10px; }

        /* TABEL UTAMA (GRID) */
        .main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 8px; text-align: center; vertical-align: middle; }
        .main-table th { background-color: #f0f0f0; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .text-left { text-align: left !important; }

        /* SIGNATURE / APPROVAL */
        .signature-box { height: 60px; }
        
        /* FOOTER */
        .footer { margin-top: 30px; font-size: 10px; font-style: italic; }
        .qr-placeholder { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

    <div class="container">
        
        <table class="header-table">
            <tr>
                <td width="20%">
                    <h2 style="color: #004B49; margin:0;">wilmar</h2>
                </td>
                <td width="60%" class="title">
                    KARTU IZIN KELUAR KARYAWAN ( IKK )
                </td>
                <td width="20%" class="doc-no">
                    F/MNA-ADM-00-017<br>Rev.3
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td class="label">Nama</td><td class="colon">:</td>
                <td>{{ $permit->user->name }}</td>
                <td class="label" style="text-align: right">Code :</td>
                <td>1. Tugas</td>
            </tr>
            <tr>
                <td class="label">NIK</td><td class="colon">:</td>
                <td>{{ $permit->user->nik ?? '-' }}</td>
                <td></td>
                <td>2. Izin Pribadi</td>
            </tr>
            <tr>
                <td class="label">Departement</td><td class="colon">:</td>
                <td>{{ $permit->user->department->name }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td><td class="colon">:</td>
                <td>{{ $permit->user->position ?? '-' }}</td>
            </tr>
        </table>

        <table class="main-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Alasan Meninggalkan Pekerjaan</th>
                    <th width="5%">Code</th>
                    <th width="10%">Paraf Atasan</th>
                    <th width="30%">Diisi Oleh Tim Security</th>
                </tr>
                <tr>
                    <th colspan="5" style="border: none; background: #fff;"></th>
                    <th style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse; margin: -1px;">
                            <tr>
                                <td colspan="2" style="border-bottom: 1px solid black; font-size: 9px;">Keluar</td>
                                <td colspan="2" style="border-bottom: 1px solid black; font-size: 9px;">Masuk</td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 9px; border-right: 1px solid black;">Jam</td>
                                <td width="50%" style="font-size: 9px;">Paraf</td>
                                <td width="50%" style="font-size: 9px; border-right: 1px solid black;">Jam</td>
                                <td width="50%" style="font-size: 9px;">Paraf</td>
                            </tr>
                        </table>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $permit->created_at->format('d/m/Y') }}</td>
                    <td class="text-left">{{ $permit->reason }}</td>
                    <td>{{ $permit->permit_type == 'tugas' ? '1' : '2' }}</td>
                    <td class="signature-box">
                        <div style="font-size: 8px; color: green;">Approved</div>
                        {{ $permit->approver->name ?? 'HOD' }}
                    </td>
                    
                    <td style="padding: 0;">
                        <table style="width: 100%; height: 60px; border-collapse: collapse;">
                            <tr>
                                <td style="width: 25%; border-right: 1px solid black;"></td>
                                <td style="width: 25%; border-right: 1px solid black;"></td>
                                <td style="width: 25%; border-right: 1px solid black;"></td>
                                <td style="width: 25%;"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <strong>Keterangan:</strong>
            <ul>
                <li>Karyawan yang akan keluar harus meminta paraf atasan terlebih dahulu.</li>
                <li>Tim Security bertugas mengisi jam keluar/masuk.</li>
                <li>Izin pribadi max 2 jam (Sesuai peraturan perusahaan).</li>
            </ul>
        </div>

        <div class="qr-placeholder">
            <p style="font-size: 10px; margin-bottom: 5px;">Scan untuk Validasi Digital</p>
            <img src="data:image/svg+xml;base64, {{ $qrcode }}" width="80">
            <br>
            <span style="font-family: monospace; font-size: 9px;">{{ $permit->uuid }}</span>
        </div>

    </div>

</body>
</html>