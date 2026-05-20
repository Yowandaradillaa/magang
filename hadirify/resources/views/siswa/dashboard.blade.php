<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm gap-4">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Portal Siswa</span>
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Dashboard Utama</h1>
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">
                    {{ now()->translatedFormat('l, d F Y') }} — Selamat datang kembali, <strong class="text-[#0f4c75]">{{ Auth::user()->name }}</strong>! 👋
                </p>
            </div>
            
            <a href="/siswa/scan-qr" class="flex items-center gap-2.5 bg-[#00b4d8] hover:bg-[#0f4c75] text-white px-6 py-3.5 rounded-xl text-[14px] font-bold shadow-md shadow-[#00b4d8]/20 hover:shadow-lg hover:shadow-[#0f4c75]/20 transition-all transform hover:-translate-y-[2px] active:translate-y-0 duration-200">
                <i data-lucide="camera" class="w-4 h-4" stroke-width="2.5"></i>
                <span>Scan QR Absensi</span>
            </a>
        </div>

        <!-- Statistik Cards (Data Asli dari Database) -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            
            <!-- Rasio Kehadiran (Contoh Kalkulasi Sederhana) -->
            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#00b4d8]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#00b4d8]/10 text-[#00b4d8] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="line-chart" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">
                    @php
                        $total = array_sum($statistik);
                        $rasio = $total > 0 ? round(($statistik['hadir'] / $total) * 100) : 0;
                    @endphp
                    {{ $rasio }}%
                </div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Rasio Kehadiran</div>
            </div>

            <!-- Hadir -->
            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#06d6a0]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#06d6a0]/10 text-[#0cb47a] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="check-circle-2" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">{{ $statistik['hadir'] }} <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Total Hadir</div>
            </div>

            <!-- Izin & Sakit -->
            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#ffd166]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#ffd166]/20 text-[#f4a60a] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="file-text" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">{{ $statistik['izin'] + $statistik['sakit'] }} <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-medium text-[#5a6a80] mt-1 flex gap-2">
                    <span class="text-[#f4a60a] font-bold">{{ $statistik['izin'] }} Izin</span>•<span class="text-amber-600 font-bold">{{ $statistik['sakit'] }} Sakit</span>
                </div>
            </div>

            <!-- Alpa -->
            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#ef476f]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#ef476f]/10 text-[#ef476f] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="alert-triangle" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                </div>
                <div class="font-mono text-3xl font-black text-[#ef476f] tracking-tight">{{ $statistik['alpa'] }} <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Tanpa Keterangan</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <!-- Riwayat Izin Terbaru (Agar kamu bisa lihat data yang baru diinput) -->
            <div class="lg:col-span-2 bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden">
                <div class="border-b border-[#e2e8f0] p-5 flex items-center justify-between bg-[#f7f9fc]">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-[#f4a60a]/10 text-[#f4a60a] rounded-lg">
                            <i data-lucide="mail-warning" class="w-4 h-4" stroke-width="2.5"></i>
                        </div>
                        <h3 class="text-[15px] font-extrabold text-[#1a2535]">Status Pengajuan Izin</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($riwayatIzin as $izin)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div>
                                <p class="text-[13px] font-bold text-slate-700">{{ $izin->jenis }} — {{ $izin->alasan }}</p>
                                <p class="text-[11px] text-slate-400">{{ $izin->tanggal_mulai }} s/d {{ $izin->tanggal_selesai }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                {{ $izin->status == 'Pending' ? 'bg-amber-100 text-amber-600' : ($izin->status == 'Disetujui' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600') }}">
                                {{ $izin->status }}
                            </span>
                        </div>
                        @empty
                        <p class="text-center text-slate-400 text-sm italic">Belum ada pengajuan izin terbaru.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Pengumuman (Data Asli dari Database) -->
            <div class="bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden flex flex-col">
                <div class="border-b border-[#e2e8f0] p-5 flex items-center gap-2.5 bg-[#f7f9fc]">
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                        <i data-lucide="megaphone" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Pengumuman</h3>
                </div>
                
                <div class="p-5 space-y-4 flex-1 overflow-y-auto max-h-[420px]">
                    @forelse($pengumuman as $p)
                    <div class="group cursor-pointer rounded-xl border border-[#e2e8f0] p-4 bg-gradient-to-br from-white to-slate-50 hover:border-[#00b4d8] hover:shadow-sm transition-all duration-200">
                        <h4 class="text-[13.5px] font-bold text-[#1a2535] group-hover:text-[#00b4d8] transition-colors line-clamp-1">{{ $p->judul }}</h4>
                        <p class="text-[12px] text-[#5a6a80] mt-1.5 leading-relaxed line-clamp-2">{{ $p->isi }}</p>
                        <div class="mt-3 flex items-center justify-between text-[10.5px] font-bold text-[#90a0b4]">
                            <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> {{ $p->guru->name }}</span>
                            <span>{{ $p->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 text-sm italic py-10">Tidak ada pengumuman hari ini.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
    <script>lucide.createIcons();</script>
</x-app-layout>