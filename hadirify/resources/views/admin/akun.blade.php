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
        .btn-warn { background: #ffd166; color: #b07500; }
        .btn-sm { padding: 6px 10px; font-size: 12px; border-radius: 7px; justify-content: center; }
        
        .tab-bar { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; gap: 4px; }
        .tab-btn { padding: 10px 18px; border: none; background: transparent; font-family: inherit; font-size: 13.5px; font-weight: 600; color: #90a0b4; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; outline: none;}
        .tab-btn.active { color: #0f4c75; border-bottom-color: #0f4c75; }
        
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 20px; text-align: left; font-size: 13px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f7f9fc; font-weight: 700; color: #5a6a80; text-transform: uppercase; font-size: 11.5px; letter-spacing: 0.5px; }
        tr:hover td { background: #f7f9fc; }
        .badge-aktif { display: inline-flex; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; background: #06d6a0; color: white; }
    </style>

    <div x-data="{ tab: 'siswa' }" class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Kelola Akun Pengguna</h2>
                <p>Manajemen data akun siswa, guru, dan staf tata usaha.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <div class="search-bar">
                    <i data-lucide="search" class="w-4 h-4 text-[#90a0b4]"></i>
                    <input type="text" placeholder="Cari nama, NISN, atau NUPTK...">
                </div>
                <button class="btn btn-primary" onclick="alert('Form Tambah Akun akan terbuka (Fase Selanjutnya)')">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Akun
                </button>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #06d6a0; color: white; padding: 12px 16px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="tab-bar">
            <button class="tab-btn" :class="{ 'active': tab === 'siswa' }" @click="tab = 'siswa'">Data Siswa</button>
            <button class="tab-btn" :class="{ 'active': tab === 'guru' }" @click="tab = 'guru'">Data Guru</button>
        </div>

        <div x-show="tab === 'siswa'" class="fade-in">
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>NISN</th>
                            <th>Nama Lengkap</th>
                            <th>Kelas</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users->where('role', 'siswa') as $siswa)
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">{{ $siswa->nisn ?? 'Belum Diatur' }}</code></td>
                            <td><b>{{ $siswa->name }}</b></td>
                            <td>{{ $siswa->kelas->nama_kelas ?? 'Belum Punya Kelas' }}</td>
                            <td>{{ $siswa->email }}</td>
                            <td><span class="badge-aktif">Aktif</span></td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                
                                <form action="{{ route('admin.users.reset', $siswa->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-warn btn-sm" onclick="return confirm('Reset password ke NISN?')">
                                        <i data-lucide="key" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center; padding: 20px; color:#90a0b4;">Belum ada data akun siswa.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="tab === 'guru'" style="display: none;" class="fade-in">
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>NUPTK</th>
                            <th>Nama Lengkap</th>
                            <th>Mata Pelajaran Utama</th>
                            <th>Wali Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users->where('role', 'guru') as $guru)
                        <tr>
                            <td><code style="font-family:'Space Mono',monospace;font-size:12px;">{{ $guru->nuptk ?? 'Belum Diatur' }}</code></td>
                            <td><b>{{ $guru->name }}</b></td>
                            <td>Data Mapel (Menyusul)</td>
                            <td>Data Kelas (Menyusul)</td>
                            <td style="display:flex; gap:6px;">
                                <button class="btn btn-outline btn-sm"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                <button class="btn btn-danger btn-sm"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                
                                <form action="{{ route('admin.users.reset', $guru->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="btn btn-warn btn-sm" onclick="return confirm('Reset password ke NUPTK?')">
                                        <i data-lucide="key" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center; padding: 20px; color:#90a0b4;">Belum ada data akun guru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>