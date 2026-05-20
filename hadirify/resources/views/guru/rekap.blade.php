<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .form-row { display: flex; gap: 16px; margin-bottom: 20px; }
        .form-field { flex: 1; max-width: 200px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-success { background: #06d6a0; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; }
        tr:hover td { background: #f7faff; }
    </style>

    <div class="animate-in fade-in duration-300">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Rekap & Export Presensi</h2>
                <p>Lihat rekap statistik dan unduh laporan berkas bulanan</p>
            </div>
            <div style="display:flex; gap:10px;">
                <button class="btn btn-outline" onclick="alert('Mengunduh dokumen PDF...')">📄 Export PDF</button>
                <button class="btn btn-success" onclick="alert('Mengunduh lembar Excel...')">📊 Export Excel</button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label>Kelas</label>
                <select><option>X-A</option><option>X-B</option><option>XI-A</option></select>
            </div>
            <div class="form-field">
                <label>Bulan</label>
                <select><option>Mei 2026</option><option>April 2026</option></select>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>📊 Laporan Kehadiran Kelas X-A — Mei 2026</h3></div>
            <table>
                <thead>
                    <tr><th>No</th><th>Nama Siswa</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th><th>Persentase</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Ahmad Rizki</td><td>16 Hari</td><td>2 Hari</td><td>0 Hari</td><td>0 Hari</td><td><span style="color:#0cb47a; font-weight:700;">100%</span></td></tr>
                    <tr><td>2</td><td>Budi Santoso</td><td>17 Hari</td><td>0 Hari</td><td>1 Hari</td><td>0 Hari</td><td><span style="color:#0cb47a; font-weight:700;">94%</span></td></tr>
                    <tr><td>3</td><td>Citra Dewi</td><td>15 Hari</td><td>2 Hari</td><td>0 Hari</td><td>1 Hari</td><td><span style="color:#ffd166; font-weight:700;">83%</span></td></tr>
                    <tr><td>4</td><td>Dewi Kusuma</td><td>14 Hari</td><td>0 Hari</td><td>2 Hari</td><td>2 Hari</td><td><span style="color:#ef476f; font-weight:700;">78%</span></td></tr>
                    <tr><td>5</td><td>Eko Prasetyo</td><td>18 Hari</td><td>0 Hari</td><td>0 Hari</td><td>0 Hari</td><td><span style="color:#0cb47a; font-weight:700;">100%</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</x-guru-layout>