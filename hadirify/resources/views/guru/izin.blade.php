<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        
        .tab-bar { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; gap: 4px; }
        .tab-btn { padding: 10px 18px; border: none; background: transparent; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; font-weight: 600; color: #90a0b4; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; outline: none; }
        .tab-btn.active { color: #0f4c75; border-bottom-color: #0f4c75; }
        
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; vertical-align: middle; color: #1a2535; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f7faff; }
        
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.2px; }
        .badge-sakit { background: rgba(0,180,216,0.12); color: #0a8ba8; }
        .badge-izin { background: rgba(255,209,102,0.18); color: #b07500; }
        .badge-approve { background: rgba(6,214,160,0.12); color: #0cb47a; }
        .badge-tolak { background: rgba(239,71,111,0.12); color: #c0213f; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .18s; text-decoration: none; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }
        .btn-success { background: #06d6a0; color: white; }
        .btn-success:hover { background: #04b88a; }
        .btn-danger { background: #ef476f; color: white; }
        .btn-danger:hover { background: #c0213f; }
        
        .fade-in { animation: fadeIn .3s ease; }
        
        /* PERBAIKAN: Gunakan double @@ agar tidak error di Laravel Blade */
        @@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
    </style>

    <div x-data="{ tab: 'pending' }" class="fade-in">
        
        <div class="page-header">
            <div class="page-header-left">
                <h2>Kelola Izin Siswa</h2>
                <p>Review dan approve pengajuan izin</p>
            </div>
        </div>
        
        <div class="tab-bar">
            <button class="tab-btn" :class="{ 'active': tab === 'pending' }" x-on:click="tab = 'pending'">⏳ Pending (3)</button>
            <button class="tab-btn" :class="{ 'active': tab === 'all' }" x-on:click="tab = 'all'">📋 Semua Izin</button>
        </div>
        
        <div x-show="tab === 'pending'" class="fade-in">
            <div class="card">
                <table>
                    <thead>
                        <tr><th>Siswa</th><th>Jenis</th><th>Tanggal</th><th>Alasan</th><th>Lampiran</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><b>Ahmad Rizki</b></td>
                            <td><span class="badge badge-sakit">Sakit</span></td>
                            <td>11–12 Mei 2026</td>
                            <td>Demam tinggi</td>
                            <td><a href="#" style="color:#00b4d8;font-size:12px;font-weight:700;">📎 surat.pdf</a></td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn btn-success btn-sm" onclick="this.closest('tr').remove();alert('Izin disetujui!')">✓ Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove();alert('Izin ditolak!')">✗ Tolak</button>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Dewi Kusuma</b></td>
                            <td><span class="badge badge-izin">Izin</span></td>
                            <td>11 Mei 2026</td>
                            <td>Acara keluarga</td>
                            <td>—</td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn btn-success btn-sm" onclick="this.closest('tr').remove()">✓ Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✗ Tolak</button>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Rizky Pratama</b></td>
                            <td><span class="badge badge-izin">Izin</span></td>
                            <td>12 Mei 2026</td>
                            <td>Keperluan penting</td>
                            <td>—</td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn btn-success btn-sm" onclick="this.closest('tr').remove()">✓ Approve</button>
                                <button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">✗ Tolak</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div x-show="tab === 'all'" style="display: none;" class="fade-in">
            <div class="card">
                <table>
                    <thead>
                        <tr><th>Siswa</th><th>Jenis</th><th>Tanggal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ahmad Rizki</td>
                            <td><span class="badge badge-sakit">Sakit</span></td>
                            <td>7–8 Mei</td>
                            <td><span class="badge badge-approve">Disetujui</span></td>
                        </tr>
                        <tr>
                            <td>Budi Santoso</td>
                            <td><span class="badge badge-izin">Izin</span></td>
                            <td>5 Mei</td>
                            <td><span class="badge badge-approve">Disetujui</span></td>
                        </tr>
                        <tr>
                            <td>Fitri Rahayu</td>
                            <td><span class="badge badge-izin">Izin</span></td>
                            <td>3 Mei</td>
                            <td><span class="badge badge-tolak">Ditolak</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-guru-layout>