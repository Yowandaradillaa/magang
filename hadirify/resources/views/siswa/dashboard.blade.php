<x-app-layout>
    <!-- Container Utama: Selaras dengan Admin/Guru (Fixed Header, Scrollable Content) -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-5 sm:px-6 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <!-- Icon Profile (Sky Blue Theme) -->
                    <div class="flex-shrink-0 w-11 h-11 bg-sky-600 flex items-center justify-center rounded-lg shadow-lg shadow-sky-200/50">
                        <i data-lucide="user" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Halo, {{ Auth::user()->name }}!</h2>
                        <p class="text-xs text-slate-500 font-medium flex items-center gap-1.5">
                            <span class="px-1.5 py-0.5 bg-sky-50 text-sky-600 text-[10px] font-black rounded border border-sky-100 uppercase">Siswa</span>
                            • Portal Akademik Hadirify
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <!-- Realtime Clock & Date -->
                    <div class="flex items-center gap-3 px-4 py-2 bg-slate-50/80 rounded-xl border border-slate-100">
                        <div class="text-right">
                            <p id="realtime-date" class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                {{ now()->translatedFormat('d F Y') }}
                            </p>
                            <p id="realtime-clock" class="text-sm font-bold text-[#0b1e36] font-mono leading-none">
                                00:00:00
                            </p>
                        </div>
                        <div class="w-[1px] h-6 bg-slate-200 mx-1"></div>
                        <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- Quick Scan Button -->
                    <a href="/siswa/scan-qr" class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 hover:bg-black text-white text-[11px] font-black uppercase tracking-widest rounded-lg shadow-lg transition-all active:scale-95">
                        <i data-lucide="camera" class="w-4 h-4"></i>
                        Scan Absensi
                    </a>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: SCROLLABLE CONTENT ================= -->
        <div class="flex-1 min-h-0 space-y-6 overflow-y-auto no-scrollbar pb-10">
            
            <!-- STATS GRID (Aksen Garis Kiri) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $total = array_sum($statistik);
                    $rasio = $total > 0 ? round(($statistik['hadir'] / $total) * 100) : 0;
                    
                    $siswaStats = [
                        ['label' => 'Rasio Hadir', 'val' => $rasio.'%', 'icon' => 'trending-up', 'color' => 'bg-slate-900'],
                        ['label' => 'Total Hadir', 'val' => $statistik['hadir'].' Hari', 'icon' => 'check-circle-2', 'color' => 'bg-emerald-500'],
                        ['label' => 'Izin / Sakit', 'val' => ($statistik['izin'] + $statistik['sakit']).' Hari', 'icon' => 'file-text', 'color' => 'bg-amber-500'],
                        ['label' => 'Alpa', 'val' => $statistik['alpa'].' Hari', 'icon' => 'alert-triangle', 'color' => 'bg-rose-500'],
                    ];
                @endphp
                
                @foreach($siswaStats as $s)
                <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] {{ $s['color'] }}"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $s['label'] }}</p>
                            <div class="text-2xl font-bold text-[#0b1e36] tracking-tighter leading-none">{{ $s['val'] }}</div>
                        </div>
                        <div class="p-1.5 bg-slate-50 text-slate-300 group-hover:text-slate-900 transition-colors">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- SPLIT GRID: IZIN & PENGUMUMAN -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI: STATUS IZIN (Accent Amber) -->
                <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-amber-500"></div>
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail-warning" class="w-4 h-4 text-amber-500"></i>
                            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Status Pengajuan Izin</h3>
                        </div>
                        <a href="/siswa/izin" class="text-[10px] font-black text-sky-600 hover:underline uppercase">Ajukan Baru</a>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($riwayatIzin as $izin)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors flex items-center justify-between group">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.5 rounded text-[8px] font-black uppercase border {{ $izin->jenis == 'Sakit' ? 'bg-sky-50 text-sky-600 border-sky-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                        {{ $izin->jenis }}
                                    </span>
                                    <h4 class="text-xs font-bold text-slate-700 tracking-tight">{{ Str::limit($izin->alasan, 35) }}</h4>
                                </div>
                                <p class="text-[10px] text-slate-400 font-medium flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M') }} — {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-tighter border
                                {{ $izin->status == 'Pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($izin->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200') }}">
                                {{ $izin->status }}
                            </span>
                        </div>
                        @empty
                        <div class="py-16 text-center opacity-30">
                            <i data-lucide="inbox" class="w-10 h-10 mb-2 mx-auto"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">Belum ada pengajuan</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- KOLOM KANAN: PENGUMUMAN (Accent Sky) -->
                <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-sky-600"></div>
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i data-lucide="megaphone" class="w-4 h-4 text-sky-600"></i>
                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Warta Kelas</h3>
                    </div>

                    <div class="p-5 space-y-4">
                        @forelse($pengumuman as $p)
                        <div class="group p-4 bg-slate-50 border border-slate-100 rounded-xl hover:border-sky-500 transition-all cursor-default">
                            <h4 class="text-xs font-extrabold text-slate-800 group-hover:text-sky-600 transition-colors">{{ $p->judul }}</h4>
                            <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed line-clamp-2">{{ $p->isi }}</p>
                            <div class="mt-3 pt-3 border-t border-slate-200/60 flex items-center justify-between">
                                <span class="text-[9px] font-bold text-slate-400 uppercase flex items-center gap-1">
                                    <i data-lucide="user-check" class="w-3 h-3"></i> {{ $p->guru->name }}
                                </span>
                                <span class="text-[9px] font-mono text-slate-300">{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center opacity-30">
                            <p class="text-[10px] font-black uppercase tracking-widest italic">Tidak ada warta</p>
                        </div>
                        @endforelse
                    </div>
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
            if(el) el.textContent = timeStr;
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>