<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
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

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            
            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#00b4d8]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#00b4d8]/10 text-[#00b4d8] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="line-chart" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="text-[10px] font-black tracking-wider text-[#0cb47a] bg-[#06d6a0]/10 px-2 py-0.5 rounded-full uppercase">Sangat Baik</span>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">94%</div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Rasio Kehadiran</div>
            </div>

            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#06d6a0]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#06d6a0]/10 text-[#0cb47a] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="check-circle-2" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="text-[11px] font-bold text-[#5a6a80]">Bulan Ini</span>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">18 <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Hadir Tepat Waktu</div>
            </div>

            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#ffd166]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#ffd166]/20 text-[#f4a60a] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="file-text" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="text-[11px] font-bold text-[#5a6a80]">Berkas Sah</span>
                </div>
                <div class="font-mono text-3xl font-black text-[#1a2535] tracking-tight">2 <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-medium text-[#5a6a80] mt-1 flex gap-2">
                    <span class="text-[#f4a60a] font-bold">1 Izin</span>•<span class="text-amber-600 font-bold">1 Sakit</span>
                </div>
            </div>

            <div class="group bg-white p-6 rounded-[22px] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-[#ef476f]"></div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-[#ef476f]/10 text-[#ef476f] rounded-xl group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="alert-triangle" class="w-5 h-5" stroke-width="2.5"></i>
                    </div>
                    <span class="text-[10px] font-black tracking-wider text-[#ef476f] bg-[#ef476f]/10 px-2 py-0.5 rounded-full uppercase">Perlu Cek</span>
                </div>
                <div class="font-mono text-3xl font-black text-[#ef476f] tracking-tight">0 <span class="text-sm font-bold text-[#90a0b4]">Hari</span></div>
                <div class="text-[12px] font-bold text-[#90a0b4] uppercase tracking-wide mt-1">Tanpa Keterangan</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <div class="lg:col-span-2 bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden">
                <div class="border-b border-[#e2e8f0] p-5 flex items-center justify-between bg-[#f7f9fc]">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                            <i data-lucide="calendar" class="w-4 h-4" stroke-width="2.5"></i>
                        </div>
                        <h3 class="text-[15px] font-extrabold text-[#1a2535]">Kalender Kehadiran</h3>
                    </div>
                    <span class="text-[12px] font-bold text-[#0f4c75]">Mei 2026</span>
                </div>
                
                <div class="p-6">
                    <div class="mb-4 grid grid-cols-7 text-center text-[11px] font-black text-[#90a0b4] uppercase tracking-widest">
                        <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                    </div>
                    
                    <div class="grid grid-cols-7 gap-2.5">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="h-14 rounded-xl bg-slate-50/40 border border-dashed border-[#e2e8f0]/60"></div>
                        @endfor
                        
                        @for ($date = 1; $date <= 28; $date++)
                            @php
                                $isWeekend = in_array(($date + 4) % 7, [5, 6]);
                                $boxStyle = 'bg-emerald-50 text-[#0cb47a] border border-emerald-100/80 hover:bg-emerald-100/50';
                                $badge = 'H';
                                
                                if ($isWeekend) {
                                    $boxStyle = 'bg-slate-50 text-slate-300 border border-transparent';
                                    $badge = '•';
                                } elseif ($date == 12) {
                                    $boxStyle = 'bg-rose-50 text-[#ef476f] border border-rose-100/80 hover:bg-rose-100/50';
                                    $badge = 'A';
                                } elseif ($date == 19) {
                                    $boxStyle = 'bg-amber-50 text-[#f4a60a] border border-amber-100/80 hover:bg-amber-100/50';
                                    $badge = 'I';
                                }
                            @endphp
                            
                            <div class="group flex flex-col items-center justify-center h-14 rounded-xl text-[14px] font-black transition-all duration-200 cursor-pointer transform hover:scale-[1.04] {{ $boxStyle }}">
                                <span>{{ $date }}</span>
                                <span class="text-[9px] font-black opacity-75 mt-0.5 group-hover:scale-110 transition-transform">{{ $badge }}</span>
                            </div>
                        @endfor
                    </div>
                    
                    <div class="mt-6 flex flex-wrap gap-5 border-t border-[#e2e8f0] pt-5">
                        <div class="flex items-center gap-2 text-[12px] font-bold text-[#5a6a80]">
                            <span class="w-3 h-3 rounded-full bg-[#06d6a0]"></span>
                            <span>Hadir (H)</span>
                        </div>
                        <div class="flex items-center gap-2 text-[12px] font-bold text-[#5a6a80]">
                            <span class="w-3 h-3 rounded-full bg-[#ffd166]"></span>
                            <span>Izin/Sakit (I)</span>
                        </div>
                        <div class="flex items-center gap-2 text-[12px] font-bold text-[#5a6a80]">
                            <span class="w-3 h-3 rounded-full bg-[#ef476f]"></span>
                            <span>Alpa (A)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden flex flex-col">
                <div class="border-b border-[#e2e8f0] p-5 flex items-center gap-2.5 bg-[#f7f9fc]">
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                        <i data-lucide="megaphone" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Pengumuman</h3>
                </div>
                
                <div class="p-5 space-y-4 flex-1 overflow-y-auto max-h-[420px]">
                    <div class="group cursor-pointer rounded-xl border border-[#e2e8f0] p-4 bg-gradient-to-br from-white to-slate-50 hover:border-[#00b4d8] hover:shadow-sm transition-all duration-200">
                        <h4 class="text-[13.5px] font-bold text-[#1a2535] group-hover:text-[#00b4d8] transition-colors line-clamp-1">Pelaksanaan UTS Semester Genap</h4>
                        <p class="text-[12px] text-[#5a6a80] mt-1.5 leading-relaxed line-clamp-2">Ujian Tengah Semester akan diadakan mulai pekan depan. Harap persiapkan kartu ujian dan tidak ada tunggakan administrasi.</p>
                        <div class="mt-3 flex items-center justify-between text-[10.5px] font-bold text-[#90a0b4]">
                            <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> Kurikulum</span>
                            <span>1 jam lalu</span>
                        </div>
                    </div>

                    <div class="group cursor-pointer rounded-xl border border-[#e2e8f0] p-4 bg-gradient-to-br from-white to-slate-50 hover:border-[#00b4d8] hover:shadow-sm transition-all duration-200">
                        <h4 class="text-[13.5px] font-bold text-[#1a2535] group-hover:text-[#00b4d8] transition-colors line-clamp-1">Libur Hari Raya Nasional</h4>
                        <p class="text-[12px] text-[#5a6a80] mt-1.5 leading-relaxed line-clamp-2">Sehubungan dengan hari libur nasional, kegiatan belajar mengajar ditiadakan pada hari Kamis mendatang.</p>
                        <div class="mt-3 flex items-center justify-between text-[10.5px] font-bold text-[#90a0b4]">
                            <span class="flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> Humas</span>
                            <span>Kemarin</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>