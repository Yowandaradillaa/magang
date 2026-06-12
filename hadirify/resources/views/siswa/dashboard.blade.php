<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
        <!-- Header Welcome Board -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm gap-6 relative overflow-hidden">
            <!-- Subtle backdrop ornament -->
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-sky-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-sky-600 uppercase bg-sky-500/10 px-3.5 py-1.5 rounded-full border border-sky-500/10">Portal Akademik</span>
                <h1 class="text-2xl md:text-3xl font-black text-[#0b1e36] tracking-tight mt-3">Dashboard Utama Siswa</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1.5 flex flex-wrap items-center gap-1.5">
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="text-slate-300">•</span>
                    <span>Selamat datang kembali, <strong class="text-[#0b1e36] font-bold">{{ Auth::user()->name }}</strong>! 👋</span>
                </p>
            </div>
            
            <a href="/siswa/scan-qr" class="w-full md:w-auto shrink-0 flex items-center justify-center gap-2.5 bg-gradient-to-r from-sky-600 to-sky-700 hover:from-sky-700 hover:to-sky-800 text-white px-7 py-4 rounded-xl text-[14px] font-bold shadow-lg shadow-sky-600/20 hover:shadow-xl hover:shadow-sky-600/30 transition-all duration-300 transform hover:-translate-y-[2px] active:translate-y-0 cursor-pointer">
                <i data-lucide="camera" class="w-4.5 h-4.5" stroke-width="2.5"></i>
                <span>Scan QR Absensi</span>
            </a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            
            <!-- Kehadiran Rate Card -->
            <div class="group bg-[#0b1e36] text-white p-6 rounded-[24px] shadow-lg border border-white/5 hover:shadow-xl transition-all duration-300 relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute -right-8 -bottom-8 w-24 h-24 bg-white/5 rounded-full blur-[20px] pointer-events-none group-hover:bg-white/10 transition-colors"></div>
                <div class="flex justify-between items-center mb-4">
                    <div class="p-3 bg-white/10 text-amber-400 rounded-xl">
                        <i data-lucide="trending-up" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400 bg-white/5 px-2 py-0.5 rounded-md">Rasio</span>
                </div>
                <div class="font-mono text-3xl font-black tracking-tight text-white">
                    @php
                        $total = array_sum($statistik);
                        $rasio = $total > 0 ? round(($statistik['hadir'] / $total) * 100) : 0;
                    @endphp
                    {{ $rasio }}%
                </div>
                <div class="text-[11px] font-black text-amber-400 uppercase tracking-widest mt-2">Rasio Kehadiran</div>
            </div>

            <!-- Total Hadir Card -->
            <div class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-emerald-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform duration-300 border border-emerald-100">
                        <i data-lucide="check-circle-2" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#0b1e36] tracking-tight">{{ $statistik['hadir'] }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-2">Total Hadir</div>
            </div>

            <!-- Total Izin / Sakit Card -->
            <div class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-amber-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition-transform duration-300 border border-amber-100">
                        <i data-lucide="file-text" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#0b1e36] tracking-tight">{{ $statistik['izin'] + $statistik['sakit'] }} <span class="text-xs font-bold text-slate-400">Hari</span></div>
                <div class="text-[11px] font-medium text-slate-500 mt-2 flex items-center gap-2">
                    <span class="text-amber-600 font-extrabold bg-amber-50 px-2 py-0.5 rounded">{{ $statistik['izin'] }} Izin</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-sky-600 font-extrabold bg-sky-50 px-2 py-0.5 rounded">{{ $statistik['sakit'] }} Sakit</span>
                </div>
            </div>

            <!-- Total Alpa Card -->
            <div class="group bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-rose-500"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-rose-50 text-rose-600 rounded-xl group-hover:scale-110 transition-transform duration-300 border border-rose-100">
                        <i data-lucide="alert-triangle" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-rose-600 tracking-tight">{{ $statistik['alpa'] }} <span class="text-xs font-bold text-rose-400">Hari</span></div>
                <div class="text-[11px] font-black text-rose-400 uppercase tracking-widest mt-2">Tanpa Keterangan (Alpa)</div>
            </div>
        </div>

        <!-- Split Grid (Izin & Pengumuman) -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            
            <!-- Pengajuan Izin Section -->
            <div class="lg:col-span-2 bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 p-5 bg-slate-50/50 flex items-center gap-3">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-xl border border-amber-100">
                            <i data-lucide="mail-warning" class="w-4 h-4" stroke-width="2.5"></i>
                        </div>
                        <h3 class="text-[15px] font-extrabold text-[#0b1e36]">Status Pengajuan Perizinan</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        @forelse($riwayatIzin as $izin)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:border-slate-200 transition-colors">
                            <div class="space-y-1">
                                <p class="text-[13px] font-bold text-[#0b1e36] flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase {{ $izin->jenis == 'Sakit' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700' }}">{{ $izin->jenis }}</span>
                                    <span>{{ $izin->alasan }}</span>
                                </p>
                                <p class="text-[11.5px] font-medium text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    <span>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M') }}</span>
                                </p>
                            </div>
                            <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border
                                {{ $izin->status == 'Pending' ? 'bg-amber-50 text-amber-600 border-amber-200' : ($izin->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200') }}">
                                {{ $izin->status }}
                            </span>
                        </div>
                        @empty
                        <div class="py-10 text-center flex flex-col items-center justify-center gap-3">
                            <div class="p-4 bg-slate-100 text-slate-400 rounded-full">
                                <i data-lucide="inbox" class="w-8 h-8"></i>
                            </div>
                            <p class="text-slate-400 text-sm italic font-medium">Belum ada pengajuan izin terbaru.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="/siswa/izin" class="text-[12px] font-extrabold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                        Ajukan Izin Baru <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Pengumuman Section -->
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 p-5 bg-slate-50/50 flex items-center gap-3">
                        <div class="p-2 bg-sky-50 text-sky-600 rounded-xl border border-sky-100">
                            <i data-lucide="megaphone" class="w-4 h-4" stroke-width="2.5"></i>
                        </div>
                        <h3 class="text-[15px] font-extrabold text-[#0b1e36]">Pengumuman Kelas</h3>
                    </div>
                    
                    <div class="p-5 space-y-4 max-h-[400px] overflow-y-auto">
                        @forelse($pengumuman as $p)
                        <div class="group cursor-pointer rounded-2xl border border-slate-100 p-4 bg-gradient-to-br from-white to-slate-50/50 hover:border-sky-500 hover:shadow-md transition-all duration-300">
                            <h4 class="text-[13.5px] font-bold text-[#0b1e36] group-hover:text-sky-600 transition-colors line-clamp-1">{{ $p->judul }}</h4>
                            <p class="text-[12px] text-slate-500 mt-2 leading-relaxed line-clamp-2">{{ $p->isi }}</p>
                            
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-[10.5px] font-bold text-slate-400">
                                <span class="flex items-center gap-1"><i data-lucide="user" class="w-3.5 h-3.5"></i> {{ $p->guru->name }}</span>
                                <span>{{ $p->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center flex flex-col items-center justify-center gap-3">
                            <div class="p-4 bg-slate-100 text-slate-400 rounded-full">
                                <i data-lucide="megaphone-off" class="w-8 h-8"></i>
                            </div>
                            <p class="text-slate-400 text-sm italic font-medium">Tidak ada pengumuman kelas hari ini.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                    <a href="/siswa/notifikasi" class="text-[12px] font-extrabold text-sky-600 hover:text-sky-700 flex items-center gap-1">
                        Lihat Semua Notifikasi <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <script>lucide.createIcons();</script>
</x-app-layout>