<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Siswa SMA Muhammadiyah 7 - {{ $periode }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* ================= KOP SURAT / HEADER ================= */
        .header-container {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
        }

        .header-title-org {
            font-size: 11px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 2px;
        }

        .header-title-school {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .header-subtitle {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            margin-top: 6px;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .header-periode {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .divider-double {
            border: none;
            border-top: 2.5px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            height: 3px;
            margin: 12px 0 15px 0;
        }

        /* ================= METADATA INFO ================= */
        .meta-table {
            width: 100%;
            margin-bottom: 14px;
            font-size: 10px;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .badge-kelas {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            padding: 2px 8px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            display: inline-block;
        }

        /* ================= SUMMARY STATS BOX ================= */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .summary-cell {
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            padding: 8px 10px;
            text-align: center;
            width: 20%;
        }

        .summary-label {
            font-size: 8px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }

        .color-hadir { color: #059669; }
        .color-sakit { color: #d97706; }
        .color-izin { color: #0284c7; }
        .color-alpa { color: #e11d48; }

        /* ================= DATA TABLE ================= */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th, 
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 10px;
        }

        .data-table thead th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            padding: 8px 6px;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .data-table td.text-center {
            text-align: center;
        }

        .data-table td.text-left {
            text-align: left;
        }

        .data-table td.font-bold {
            font-weight: 700;
        }

        .data-table tfoot td {
            background-color: #e2e8f0;
            font-weight: 800;
            font-size: 10px;
            color: #0f172a;
            border-top: 2px solid #0f172a;
        }

        .persen-badge {
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            display: inline-block;
        }

        .persen-tinggi { color: #059669; background-color: #ecfdf5; }
        .persen-sedang { color: #d97706; background-color: #fffbeb; }
        .persen-rendah { color: #e11d48; background-color: #fff1f2; }

        /* ================= TANDA TANGAN ================= */
        .signature-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signature-table td {
            vertical-align: top;
            font-size: 10px;
        }

        .signature-box {
            text-align: center;
            width: 220px;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        /* ================= FOOTER ================= */
        .footer-note {
            margin-top: 20px;
            font-size: 8px;
            color: #94a3b8;
            font-style: italic;
            border-top: 1px dotted #cbd5e1;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- ================= HEADER / KOP ================= -->
    <div class="header-container">
        <div class="header-title-org">Majelis Pendidikan Dasar dan Menengah PDM Kota Yogyakarta</div>
        <div class="header-title-school">SMA Muhammadiyah 7 Yogyakarta</div>
        <div class="header-subtitle">Laporan Kehadiran Siswa</div>
        <div class="header-periode">Periode Rekapitulasi: <b>{{ $periode }}</b></div>
    </div>

    <div class="divider-double"></div>

    <!-- ================= METADATA ================= -->
    <table class="meta-table">
        <tr>
            <td width="15%"><strong>Filter Kelas</strong></td>
            <td width="35%">: <span class="badge-kelas">{{ $namaKelasDipilih ?? 'Semua Kelas' }}</span></td>
            <td width="20%"><strong>Waktu Ekspor</strong></td>
            <td width="30%">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td><strong>Status Dokumen</strong></td>
            <td>: Dokumen Resmi Terverifikasi</td>
            <td><strong>Dicetak Oleh</strong></td>
            <td>: {{ auth()->user()->name ?? 'Administrator' }}</td>
        </tr>
    </table>

    <!-- ================= MINI STATS OVERVIEW ================= -->
    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <div class="summary-label">Total Hadir</div>
                <div class="summary-value color-hadir">{{ number_format($totals['total_hadir'] ?? 0) }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Total Sakit</div>
                <div class="summary-value color-sakit">{{ number_format($totals['total_sakit'] ?? 0) }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Total Izin</div>
                <div class="summary-value color-izin">{{ number_format($totals['total_izin'] ?? 0) }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Total Alpa</div>
                <div class="summary-value color-alpa">{{ number_format($totals['total_alpa'] ?? 0) }}</div>
            </td>
            <td class="summary-cell">
                <div class="summary-label">Rata-rata Presensi</div>
                <div class="summary-value">{{ $totals['rata_kehadiran'] ?? 0 }}%</div>
            </td>
        </tr>
    </table>

    <!-- ================= TABEL REKAP ================= -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="33%">Nama Kelas</th>
                <th width="12%">Total Hadir</th>
                <th width="12%">Total Sakit</th>
                <th width="12%">Total Izin</th>
                <th width="12%">Total Alpa</th>
                <th width="14%">Persentase Kehadiran (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $item)
                @php
                    $persen = $item['persentase'] ?? 0;
                    if ($persen >= 90) { $pClass = 'persen-tinggi'; }
                    elseif ($persen >= 75) { $pClass = 'persen-sedang'; }
                    else { $pClass = 'persen-rendah'; }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left font-bold">{{ $item['nama_kelas'] }}</td>
                    <td class="text-center font-bold color-hadir">{{ number_format($item['hadir']) }}</td>
                    <td class="text-center color-sakit">{{ number_format($item['sakit']) }}</td>
                    <td class="text-center color-izin">{{ number_format($item['izin']) }}</td>
                    <td class="text-center color-alpa">{{ number_format($item['alpa']) }}</td>
                    <td class="text-center">
                        <span class="persen-badge {{ $pClass }}">{{ $persen }}%</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8; font-style: italic;">
                        Tidak ada data kehadiran yang tercatat untuk periode dan kelas yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center font-bold">TOTAL KESELURUHAN</td>
                <td class="text-center font-bold">{{ number_format($totals['total_hadir'] ?? 0) }}</td>
                <td class="text-center font-bold">{{ number_format($totals['total_sakit'] ?? 0) }}</td>
                <td class="text-center font-bold">{{ number_format($totals['total_izin'] ?? 0) }}</td>
                <td class="text-center font-bold">{{ number_format($totals['total_alpa'] ?? 0) }}</td>
                <td class="text-center font-bold">{{ $totals['rata_kehadiran'] ?? 0 }}%</td>
            </tr>
        </tfoot>
    </table>

    <!-- ================= TANDA TANGAN PENGESAHAN ================= -->
    <table class="signature-table">
        <tr>
            <td width="60%"></td>
            <td width="40%" align="right">
                <div class="signature-box">
                    <div>Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="margin-top: 3px;">Mengetahui,</div>
                    <div style="font-weight: 600;">Kepala SMA Muhammadiyah 7</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">( .................................................... )</div>
                    <div style="font-size: 9px; color: #64748b; margin-top: 2px;">NIP/NBM: ........................................</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- ================= FOOTER CATATAN ================= -->
    <div class="footer-note">
        * Dokumen ini digenerate secara otomatis melalui Sistem Informasi Presensi Siswa Hadirify - SMA Muhammadiyah 7.
    </div>

</body>
</html>
