<x-admin-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;}
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .card-body { padding: 20px 22px; }
        .form-row { display: flex; gap: 16px; margin-bottom: 0px; flex-wrap: wrap; }
        .form-field { flex: 1; min-width: 150px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select, .form-field input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .2s; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; vertical-align: middle; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; gap: 4px; }
        .badge-sakit { background: #ffd166; color: #b07500; }
        .badge-izin { background: #00b4d8; color: white; }
        .badge-hadir { background: #06d6a0; color: white; }
        .badge-alpa { background: #ef476f; color: white; }
        .status-select { padding: 6px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px; font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer; outline: none; background: white; }
        .fade-in { animation: fadeIn .3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
    </style>

    <div class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Koreksi Absensi</h2>
                <p>Mengubah atau memperbaiki data absensi siswa jika terjadi kesalahan</p>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #06d6a0; color: white; padding: 12px 16px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <!-- Nantinya form ini bisa diarahkan ke fungsi pencarian (GET) -->
                <form action="#" method="GET" class="form-row">
                    <div class="form-field">
                        <label>Nama / NISN Siswa</label>
                        <input type="text" name="search" placeholder="Cari data siswa..." value="{{ request('search') }}">
                    </div>
                    <div class="form-field" style="display:flex; align-items:flex-end;">
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i data-lucide="search" class="w-4 h-4"></i> Cari Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-[#0f4c75]"></i>
                <h3>Data Hasil Pencarian</h3>
            </div>
            <table>
                <thead><tr><th>Siswa</th><th>Kelas & Mapel</th><th>Tanggal</th><th>Status Awal</th><th>Koreksi Menjadi</th><th>Aksi</th></tr></thead>
                <tbody>
                    <!-- 🌟 BERUBAH DI SINI: Data Pencarian Absensi 🌟 -->
                    @forelse($absensis ?? [] as $absen)
                    <tr>
                        <td><b>{{ $absen->siswa->name ?? '-' }}</b><br><span style="font-size:11px;color:#90a0b4;">{{ $absen->siswa->nisn ?? '-' }}</span></td>
                        <td>{{ $absen->siswa->kelas->nama_kelas ?? '-' }}<br><span style="font-size:11px;color:#90a0b4;">{{ $absen->jadwal->mapel->nama_mapel ?? '-' }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($absen->status == 'A') <span class="badge badge-alpa">Alpa</span>
                            @elseif($absen->status == 'H') <span class="badge badge-hadir">Hadir</span>
                            @elseif($absen->status == 'I') <span class="badge badge-izin">Izin</span>
                            @else <span class="badge badge-sakit">Sakit</span>
                            @endif
                        </td>
                        
                        <!-- Form Update Status -->
                        <form action="{{ route('admin.koreksi.update', $absen->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('PUT')
                            <td>
                                <select name="status" class="status-select">
                                    <option value="H" {{ $absen->status == 'H' ? 'selected' : '' }}>H - Hadir</option>
                                    <option value="S" {{ $absen->status == 'S' ? 'selected' : '' }}>S - Sakit</option>
                                    <option value="I" {{ $absen->status == 'I' ? 'selected' : '' }}>I - Izin</option>
                                    <option value="A" {{ $absen->status == 'A' ? 'selected' : '' }}>A - Alpa</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </td>
                        </form>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center; padding: 20px; color:#90a0b4;">Silakan gunakan fitur pencarian di atas untuk memunculkan riwayat data absensi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>