<x-app-layout>
    <div class="animate-in fade-in duration-500 space-y-6 pb-8 text-slate-800">
        
        <!-- Hero Banner Utama (Clean, Tegas & Kotak Elegan) -->
        <div class="bg-[#0b1e36] text-white p-5 md:p-6 rounded-lg border border-slate-800 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/10 border border-white/15 text-[11px] font-semibold text-sky-200">
                    <i data-lucide="school" class="w-3.5 h-3.5 text-sky-300"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </span>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-white">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <p class="text-xs md:text-sm text-slate-300 font-normal">
                    Selamat datang di Portal Akademik Siswa. Pantau kehadiran dan informasi kelas Anda.
                </p>
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
                <!-- Jam & Tanggal Real-time -->
                <div class="bg-white/10 border border-white/15 rounded-md px-4 py-2 text-left sm:text-right">
                    <p id="realtime-date" class="text-[11px] font-medium text-sky-200 mb-0.5">
                        {{ now()->translatedFormat('d F Y') }}
                    </p>
                    <p id="realtime-clock" class="text-lg font-bold text-white font-mono leading-tight">
                        00:00:00 WIB
                    </p>
                </div>

                <!-- Quick Scan Button -->
                <a href="/siswa/scan-qr" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold rounded-md shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <span>Scan QR Absensi</span>
                </a>
            </div>
        </div>

        <!-- Kartu Statistik Siswa Kotak Tegas & Clean -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $total = array_sum($statistik);
                $rasio = $total > 0 ? round(($statistik['hadir'] / $total) * 100) : 0;
                
                $siswaStats = [
                    ['label' => 'Rasio Kehadiran', 'val' => $rasio.'%', 'sub' => 'Tingkat Kehadiran', 'icon' => 'percent', 'badge' => 'bg-sky-50 text-sky-600 border-sky-100', 'valColor' => 'text-[#0b1e36]'],
                    ['label' => 'Total Hadir', 'val' => $statistik['hadir'], 'sub' => 'Hari Presensi', 'icon' => 'check-circle-2', 'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'valColor' => 'text-emerald-600'],
                    ['label' => 'Izin & Sakit', 'val' => ($statistik['izin'] + $statistik['sakit']), 'sub' => 'Hari Keterangan', 'icon' => 'file-text', 'badge' => 'bg-amber-50 text-amber-600 border-amber-100', 'valColor' => 'text-[#0b1e36]'],
                    ['label' => 'Alpa', 'val' => $statistik['alpa'], 'sub' => 'Tanpa Keterangan', 'icon' => 'alert-circle', 'badge' => 'bg-rose-50 text-rose-600 border-rose-100', 'valColor' => 'text-rose-600'],
                ];
            @endphp
            
            @foreach($siswaStats as $s)
                <div class="bg-white p-5 rounded-md border border-slate-200/90 shadow-2xs hover:shadow-xs transition-all space-y-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $s['label'] }}</p>
                        <div class="w-8 h-8 rounded-md border {{ $s['badge'] }} flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="text-2xl md:text-3xl font-extrabold {{ $s['valColor'] }} tracking-tight">
                        {{ $s['val'] }}
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium">{{ $s['sub'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- SPLIT GRID: STATUS IZIN & WARTA KELAS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            
            <!-- KOLOM KIRI: STATUS PENGAJUAN IZIN -->
            <div class="lg:col-span-7 bg-white rounded-md border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-md bg-slate-100 text-[#0b1e36] flex items-center justify-center shrink-0">
                            <i data-lucide="file-signature" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-[#0b1e36]">Status Pengajuan Izin</h3>
                            <p class="text-xs text-slate-500">Riwayat permohonan izin dan sakit</p>
                        </div>
                    </div>
                    <a href="/siswa/izin" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md bg-slate-50 hover:bg-slate-100 border border-slate-200 text-xs font-semibold text-[#0b1e36] transition-colors shrink-0">
                        Ajukan Izin <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($riwayatIzin as $izin)
                        <div class="p-4 hover:bg-slate-50/70 transition-colors flex items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $izin->jenis == 'Sakit' ? 'bg-sky-50 text-sky-700 border-sky-200/70' : 'bg-amber-50 text-amber-700 border-amber-200/70' }}">
                                        {{ $izin->jenis }}
                                    </span>
                                    <h4 class="font-bold text-slate-800">{{ Str::limit($izin->alasan, 35) }}</h4>
                                </div>
                                <p class="text-[11px] text-slate-400 font-normal flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M') }} — {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold border shrink-0
                                {{ $izin->status == 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200/70' : ($izin->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/70' : 'bg-rose-50 text-rose-700 border-rose-200/70') }}">
                                {{ $izin->status }}
                            </span>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400 space-y-1">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto opacity-40"></i>
                            <p class="text-xs font-medium">Belum ada pengajuan izin tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM KANAN: WARTA KELAS (KOTAK TEGAS) -->
            <div class="lg:col-span-5 bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-md bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0">
                        <i data-lucide="megaphone" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#0b1e36]">Warta & Pengumuman Kelas</h3>
                        <p class="text-xs text-slate-500">Informasi resmi dari wali kelas dan pendidik</p>
                    </div>
                </div>

                <div class="p-4 space-y-3">
                    @forelse($pengumuman as $p)
                        <div class="p-3.5 bg-slate-50/80 border border-slate-200/70 rounded-md space-y-2">
                            <h4 class="text-xs font-bold text-slate-800">{{ $p->judul }}</h4>
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-2">{{ $p->isi }}</p>
                            <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-[11px] text-slate-400">
                                <span class="font-semibold text-slate-600 flex items-center gap-1">
                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i> {{ $p->guru->name }}
                                </span>
                                <span class="font-mono">{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 space-y-1">
                            <i data-lucide="megaphone" class="w-8 h-8 mx-auto opacity-40"></i>
                            <p class="text-xs font-medium">Tidak ada warta pengumuman saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            const timeStr = now.getHours().toString().padStart(2, '0') + ':' + 
                          now.getMinutes().toString().padStart(2, '0') + ':' + 
                          now.getSeconds().toString().padStart(2, '0');
            const el = document.getElementById('realtime-clock');
            if(el) el.textContent = `${timeStr} WIB`;
        }
        setInterval(updateClock, 1000); 
        updateClock();
    </script>
</x-app-layout>