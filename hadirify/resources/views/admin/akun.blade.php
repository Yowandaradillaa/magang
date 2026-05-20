<x-admin-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .search-bar { display: flex; align-items: center; gap: 8px; background: #f7f9fc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 8px 14px; width: 260px; }
        .search-bar input { border: none; background: transparent; font-family: inherit; font-size: 13px; color: #1a2535; outline: none; width: 100%; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        .btn-danger { background: #ef476f; color: white; }
        .btn-warn { background: #ffd166; color: #7a4e00; }
        .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 7px; justify-content: center;}
        .tab-bar { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; gap: 4px; }
        .tab-btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border: none; background: transparent; font-family: inherit; font-size: 13.5px; font-weight: 600; color: #90a0b4; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; outline: none; }
        .tab-btn.active { color: #0f4c75; border-bottom-color: #0f4c75; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; vertical-align: middle; }
        .badge-aktif { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(6,214,160,0.12); color: #0cb47a; }
        .fade-in { animation: fadeIn .3s ease; }
        @@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
    </style>

    <div x-data="{ tab: 'siswa' }" class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Kelola Akun</h2>
                <p>Tambah, edit, atau hapus akun pengguna (Guru & Siswa)</p>
            </div>
            <div style="display:flex; gap:10px;">
                <div class="search-bar">
                    <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Cari nama / ID...">
                </div>
                <button class="btn btn-primary" onclick="alert('Form Tambah User dibuka')">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah User
                </button>
            </div>
        </div>

        <div class="tab-bar">
            <button class="tab-btn" :class="{ 'active': tab === 'siswa' }" x-on:click="tab = 'siswa'">
                <i data-lucide="users" class="w-4 h-4"></i> Siswa (312)
            </button>
            <button class="tab-btn" :class="{ 'active': tab === 'guru' }" x-on:click="tab = 'guru'">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i> Guru (24)
            </button>
        </div>

        <div x-show="tab === 'siswa'" class="fade-in">
            <div class="card">
                <table>
                    <thead><tr><th>NISN</th><th>Nama</th><th>Kelas</th><th>Email</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">1234567890</code></td>
                            <td><b>Budi Santoso</b></td><td>X-A</td><td>budi@sekolah.sch.id</td>
                            <td><span class="badge-aktif">Aktif</span></td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <button class="btn btn-warn btn-sm" onclick="alert('Password direset!')"><i data-lucide="key" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">1234567891</code></td>
                            <td><b>Citra Dewi</b></td><td>X-A</td><td>citra@sekolah.sch.id</td>
                            <td><span class="badge-aktif">Aktif</span></td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <button class="btn btn-warn btn-sm"><i data-lucide="key" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="tab === 'guru'" style="display: none;" class="fade-in">
            <div class="card">
                <table>
                    <thead><tr><th>NUPTK</th><th>Nama Guru</th><th>Mapel</th><th>Wali Kelas</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">1234567800</code></td>
                            <td><b>Sari Dewi, S.Pd</b></td><td>Matematika</td><td>X-A</td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <button class="btn btn-warn btn-sm" onclick="alert('Password direset!')"><i data-lucide="key" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">1234567801</code></td>
                            <td><b>Bima Sakti, S.Pd</b></td><td>Fisika</td><td>XI-A</td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                <button class="btn btn-warn btn-sm"><i data-lucide="key" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>