<x-admin-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        .btn-danger { background: #ef476f; color: white; }
        .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 7px; justify-content: center;}
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; display: flex; align-items: center; gap: 8px;}
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; vertical-align: middle; }
        .fade-in { animation: fadeIn .3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
        @media (max-width: 1100px) { .two-col { grid-template-columns: 1fr; } }
    </style>

    <div class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Atur Kelas & Jadwal</h2>
                <p>Manajemen pembagian kelas dan jadwal pelajaran</p>
            </div>
            <button class="btn btn-primary" onclick="alert('Form Tambah Kelas dibuka')">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kelas
            </button>
        </div>

        <div class="two-col">
            <div class="card">
                <div class="card-header">
                    <h3><i data-lucide="building" class="w-5 h-5 text-[#0f4c75]"></i> Daftar Kelas Aktif</h3>
                </div>
                <table>
                    <thead><tr><th>Kelas</th><th>Wali Kelas</th><th>Total Siswa</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <!-- 🌟 BERUBAH DI SINI: Data Kelas Asli 🌟 -->
                        @forelse($kelas ?? [] as $k)
                        <tr>
                            <td><b>{{ $k->nama_kelas }}</b></td>
                            <td>{{ $k->waliGuru->name ?? 'Belum Diatur' }}</td>
                            <td>{{ $k->siswa_count ?? 0 }} Siswa</td>
                            <td style="display:flex;gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center; padding: 20px; color:#90a0b4;">Belum ada data kelas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3><i data-lucide="calendar" class="w-5 h-5 text-[#00b4d8]"></i> Daftar Jadwal Pelajaran</h3>
                    <button class="btn btn-primary btn-sm" onclick="alert('Tambah Jadwal')">
                        <i data-lucide="plus" class="w-3 h-3"></i> Jadwal
                    </button>
                </div>
                <table>
                    <thead><tr><th>Hari & Jam</th><th>Mapel</th><th>Guru Pengajar</th></tr></thead>
                    <tbody>
                        <!-- 🌟 BERUBAH DI SINI: Data Jadwal Asli 🌟 -->
                        @forelse($jadwals ?? [] as $j)
                        <tr>
                            <td>
                                <b>{{ $j->hari }}</b><br>
                                <span style="font-size:11px; color:#90a0b4;">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</span>
                            </td>
                            <td><b>{{ $j->mapel->nama_mapel ?? 'Mapel' }}</b><br><span style="font-size:11px; color:#90a0b4;">{{ $j->kelas->nama_kelas ?? 'Kelas' }}</span></td>
                            <td>{{ $j->guru->name ?? 'Guru' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center; padding: 20px; color:#90a0b4;">Belum ada jadwal terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>