<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        
        .tab-bar { display: flex; border-bottom: 2px solid #e2e8f0; margin-bottom: 20px; gap: 4px; }
        .tab-btn { padding: 10px 18px; border: none; background: transparent; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px; font-weight: 600; color: #90a0b4; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; outline: none; }
        .tab-btn.active { color: #0f4c75; border-bottom-color: #0f4c75; }
        
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 20px; text-align: left; font-size: 13px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f7f9fc; font-weight: 700; color: #5a6a80; text-transform: uppercase; font-size: 11.5px; letter-spacing: 0.5px; }
        
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 800; display: inline-flex; align-items: center; gap: 4px; }
        .badge-sakit { background: #ffd166; color: #b07500; }
        .badge-izin { background: #00b4d8; color: white; }
        .badge-approve { background: #06d6a0; color: white; }
        .badge-tolak { background: #ef476f; color: white; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-family: inherit; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all .2s; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        .btn-outline:hover { border-color: #0f4c75; color: #0f4c75; }
        .btn-approve { background: #06d6a0; color: white; }
        .btn-approve:hover { background: #05b086; }
        .btn-tolak { background: #ef476f; color: white; }
        .btn-tolak:hover { background: #d83a5f; }
    </style>

    <div x-data="{ tab: 'pending' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Persetujuan Izin</h2>
                <p>Tinjau dan kelola permohonan izin atau sakit dari siswa.</p>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #06d6a0; color: white; padding: 12px 16px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="tab-bar">
            <button class="tab-btn" :class="{ 'active': tab === 'pending' }" @click="tab = 'pending'">Menunggu Persetujuan</button>
            <button class="tab-btn" :class="{ 'active': tab === 'all' }" @click="tab = 'all'">Riwayat Izin</button>
        </div>

        <div x-show="tab === 'pending'" class="fade-in">
            <div class="card">
                <table>
                    <thead>
                        <tr><th>Siswa</th><th>Jenis</th><th>Tanggal</th><th>Lampiran</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        
                        @forelse($izins as $izin)
                        <tr>
                            <td>
                                <b>{{ $izin->siswa->name ?? 'Siswa' }}</b><br>
                                <span style="font-size:11px; color:#90a0b4;">Diajukan {{ $izin->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $izin->jenis == 'Sakit' ? 'badge-sakit' : 'badge-izin' }}">
                                    {{ $izin->jenis }}
                                </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M') }} 
                                – 
                                {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M') }}
                            </td>
                            <td>
                                @if($izin->file_surat)
                                    <a href="{{ asset('storage/' . $izin->file_surat) }}" target="_blank" class="btn btn-outline btn-sm">
                                        <i data-lucide="paperclip" class="w-4 h-4"></i> Surat
                                    </a>
                                @else
                                    <span style="font-size:11px; color:#90a0b4;">Tidak ada file</span>
                                @endif
                            </td>
                            <td style="display:flex; gap:6px;">
                                <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" class="btn btn-approve btn-sm"><i data-lucide="check" class="w-4 h-4"></i> Setujui</button>
                                </form>
                                <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="btn btn-tolak btn-sm"><i data-lucide="x" class="w-4 h-4"></i> Tolak</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#90a0b4; padding:20px;">Belum ada pengajuan izin baru dari siswa.</td>
                        </tr>
                        @endforelse
                        
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
                        
                        @forelse(\App\Models\PengajuanIzin::with('siswa')->where('status', '!=', 'Pending')->orderBy('updated_at', 'desc')->get() as $riwayat)
                        <tr>
                            <td>
                                <b>{{ $riwayat->siswa->name ?? 'Siswa' }}</b><br>
                                <span style="font-size:11px; color:#90a0b4;">Diproses {{ $riwayat->updated_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $riwayat->jenis == 'Sakit' ? 'badge-sakit' : 'badge-izin' }}">
                                    {{ $riwayat->jenis }}
                                </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($riwayat->tanggal_mulai)->translatedFormat('d M') }} 
                                – 
                                {{ \Carbon\Carbon::parse($riwayat->tanggal_selesai)->translatedFormat('d M') }}
                            </td>
                            <td>
                                <span class="badge {{ $riwayat->status == 'Disetujui' ? 'badge-approve' : 'badge-tolak' }}">
                                    {{ $riwayat->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#90a0b4; padding:20px;">Belum ada riwayat persetujuan izin.</td>
                        </tr>
                        @endforelse
                        
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-guru-layout>