<x-admin-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .form-row { display: flex; gap: 16px; margin-bottom: 20px; }
        .form-field { flex: 1; max-width: 200px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-success { background: #06d6a0; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: white; border-radius: 14px; padding: 20px 22px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .stat-card.blue::before { background: linear-gradient(90deg, #0f4c75, #00b4d8); }
        .stat-card.green::before { background: linear-gradient(90deg, #06d6a0, #0cb47a); }
        .stat-card.yellow::before { background: linear-gradient(90deg, #ffd166, #f4a60a); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef476f, #c0213f); }
        .stat-num { font-size: 28px; font-weight: 800; font-family: 'Space Mono', monospace; color: #1a2535; line-height: 1; }
        .stat-label { font-size: 12px; color: #5a6a80; margin-top: 4px; font-weight: 500; }
        
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;}
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; }
        
        .fade-in { animation: fadeIn .3s ease; }
        @@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
        @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    </style>

    <div class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Laporan Kehadiran Sekolah</h2>
                <p>Statistik dan laporan kehadiran seluruh kelas dan siswa</p>
            </div>
            <div style="display:flex; gap:10px;">
                <button class="btn btn-outline" onclick="alert('Mengunduh Laporan PDF...')">
                    <i data-lucide="file-text" class="w-4 h-4 text-rose-500"></i> PDF
                </button>
                <button class="btn btn-success" onclick="alert('Mengunduh Data Excel...')">
                    <i data-lucide="sheet" class="w-4 h-4"></i> Excel
                </button>
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label>Periode Waktu</label>
                <select><option>Mei 2026</option><option>April 2026</option><option>Semester 2</option></select>
            </div>
            <div class="form-field">
                <label>Filter Kelas</label>
                <select><option>Semua Kelas</option><option>X-A</option><option>X-B</option></select>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card blue">
                <div class="mb-3"><i data-lucide="trending-up" class="w-8 h-8 text-[#0f4c75]"></i></div>
                <div class="stat-num">89%</div>
                <div class="stat-label">Rata-rata Kehadiran Sekolah</div>
            </div>
            <div class="stat-card green">
                <div class="mb-3"><i data-lucide="check-circle" class="w-8 h-8 text-[#06d6a0]"></i></div>
                <div class="stat-num">2,340</div>
                <div class="stat-label">Total Kehadiran Bulan Ini</div>
            </div>
            <div class="stat-card yellow">
                <div class="mb-3"><i data-lucide="clipboard" class="w-8 h-8 text-[#ffd166]"></i></div>
                <div class="stat-num">187</div>
                <div class="stat-label">Total Izin / Sakit</div>
            </div>
            <div class="stat-card red">
                <div class="mb-3"><i data-lucide="x-circle" class="w-8 h-8 text-[#ef476f]"></i></div>
                <div class="stat-num">96</div>
                <div class="stat-label">Total Alpa (Tanpa Keterangan)</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#0f4c75]"></i>
                <h3>Rekapitulasi per Kelas — Mei 2026</h3>
            </div>
            <table>
                <thead><tr><th>Nama Kelas</th><th>Hadir</th><th>Sakit</th><th>Izin</th><th>Alpa</th><th>Persentase Kehadiran</th></tr></thead>
                <tbody>
                    <tr><td>X-A</td><td>512</td><td>22</td><td>18</td><td>8</td><td><span style="color:#0cb47a;font-weight:700;">91%</span></td></tr>
                    <tr><td>X-B</td><td>480</td><td>25</td><td>15</td><td>10</td><td><span style="color:#0cb47a;font-weight:700;">89%</span></td></tr>
                    <tr><td>XI-A</td><td>496</td><td>30</td><td>20</td><td>14</td><td><span style="color:#b07500;font-weight:700;">84%</span></td></tr>
                    <tr><td>XI-B</td><td>440</td><td>40</td><td>25</td><td>35</td><td><span style="color:#c0213f;font-weight:700;">73%</span></td></tr>
                    <tr><td>XII-A</td><td>412</td><td>70</td><td>109</td><td>29</td><td><span style="color:#c0213f;font-weight:700;">67%</span></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>