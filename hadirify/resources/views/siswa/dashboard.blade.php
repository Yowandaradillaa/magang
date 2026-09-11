<x-app-layout>
    <div class="animate-in fade-in duration-500 space-y-8 pb-12 text-slate-800">
        
        <!-- Header Banner (Clean White dengan Garis Vertikal Navy #0b1e36 di Kiri) -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200/60 border-l-4 border-l-[#0b1e36] flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xs">
            <div class="space-y-1.5">
                <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                    <i data-lucide="school" class="w-4 h-4 text-[#0b1e36]"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0b1e36] tracking-tight">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <p class="text-sm text-slate-500 font-normal">
                    Selamat datang di Portal Akademik Siswa. Pantau kehadiran dan informasi kelas Anda.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 shrink-0">
                <!-- Jam & Tanggal Real-time -->
                <div class="text-left sm:text-right">
                    <p id="realtime-date" class="text-xs font-medium text-slate-400 mb-0.5">
                        {{ now()->translatedFormat('d F Y') }}
                    </p>
                    <p id="realtime-clock" class="text-xl font-bold text-[#0b1e36] font-mono">
                        00:00:00 WIB
                    </p>
                </div>

                <!-- Quick Scan Button -->
                <a href="/siswa/scan-qr" class="w-full sm:w-auto px-5 py-2.5 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    <span>Scan QR Absensi</span>
                </a>
            </div>
        </div>

        <!-- Kartu Statistik Siswa (Minimalis & Proporsional) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $total = array_sum($statistik);
                $rasio = $total > 0 ? round(($statistik['hadir'] / $total) * 100) : 0;
                
                $siswaStats = [
                    ['label' => 'Rasio Kehadiran', 'val' => $rasio.'%', 'sub' => 'Tingkat Kehadiran'],
                    ['label' => 'Total Hadir', 'val' => $statistik['hadir'], 'sub' => 'Hari Presensi'],
                    ['label' => 'Izin & Sakit', 'val' => ($statistik['izin'] + $statistik['sakit']), 'sub' => 'Hari Keterangan'],
                    ['label' => 'Alpa', 'val' => $statistik['alpa'], 'sub' => 'Hari Tanpa Keterangan'],
                ];
            @endphp
            
            @foreach($siswaStats as $s)
                <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-xs space-y-1.5">
                    <p class="text-xs font-semibold text-slate-500">{{ $s['label'] }}</p>
                    <div class="text-3xl font-extrabold text-[#0b1e36] tracking-tight">
                        {{ $s['val'] }}
                    </div>
                    <p class="text-xs text-slate-400 font-normal">{{ $s['sub'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- SPLIT GRID: STATUS IZIN & WARTA KELAS -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI: STATUS PENGAJUAN IZIN -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/70 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-[#0b1e36]">Status Pengajuan Izin</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Riwayat permohonan izin dan sakit</p>
                    </div>
                    <a href="/siswa/izin" class="inline-flex items-center gap-1 text-xs font-semibold text-[#0b1e36] hover:underline">
                        Ajukan Izin Baru <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100 text-xs">
                    @forelse($riwayatIzin as $izin)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $izin->jenis == 'Sakit' ? 'bg-sky-50 text-sky-700 border-sky-200/60' : 'bg-amber-50 text-amber-700 border-amber-200/60' }}">
                                        {{ $izin->jenis }}
                                    </span>
                                    <h4 class="font-bold text-slate-800">{{ Str::limit($izin->alasan, 35) }}</h4>
                                </div>
                                <p class="text-xs text-slate-400 font-normal flex items-center gap-1">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                    {{ \Carbon\Carbon::parse($izin->tanggal_mulai)->translatedFormat('d M') }} — {{ \Carbon\Carbon::parse($izin->tanggal_selesai)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border
                                {{ $izin->status == 'Pending' ? 'bg-amber-50 text-amber-700 border-amber-200/60' : ($izin->status == 'Disetujui' ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' : 'bg-rose-50 text-rose-700 border-rose-200/60') }}">
                                {{ $izin->status }}
                            </span>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 space-y-1">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto opacity-40"></i>
                            <p class="text-xs font-medium">Belum ada pengajuan izin tercatat.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- KOLOM KANAN: WARTA KELAS -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/70 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-[#0b1e36]">Warta & Pengumuman Kelas</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi resmi dari wali kelas dan pendidik</p>
                </div>

                <div class="p-6 space-y-4">
                    @forelse($pengumuman as $p)
                        <div class="p-4 bg-slate-50/70 border border-slate-200/60 rounded-xl space-y-2">
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