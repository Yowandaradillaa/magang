<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Kehadiran Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 30px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 18px; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px 12px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; text-align: center; }
        td.center { text-align: center; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #0b1e36; color: #fff; border: none; cursor: pointer; border-radius: 5px; font-weight: bold;">Cetak / Simpan PDF</button>
        <a href="{{ route('guru.rekap') }}" style="padding: 10px 20px; background: #64748b; color: #fff; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Kembali</a>
    </div>

    <div class="header">
        <h2>Laporan Rekapitulasi Kehadiran Siswa</h2>
        <p>Kelas: <b>{{ $kelasDipilih->nama_kelas ?? '-' }}</b> | Bulan: <b>{{ $bulanInput }}</b> / Tahun: <b>{{ date('Y') }}</b></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th width="10%">Hadir (H)</th>
                <th width="10%">Sakit (S)</th>
                <th width="10%">Izin (I)</th>
                <th width="10%">Alpa (A)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekaps as $index => $r)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $r->siswa->name }}</td>
                <td class="center">{{ $r->hadir }}</td>
                <td class="center">{{ $r->sakit }}</td>
                <td class="center">{{ $r->izin }}</td>
                <td class="center">{{ $r->alpa }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic;">Tidak ada data rekap untuk kelas/bulan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>