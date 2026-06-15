<x-guru-layout>
    <!-- Container Utama: Selaras dengan Admin (Fixed Header, Scrollable Content) -->
    <div x-data="{ qrMembuka: false, kelasAktif: '' }" 
         class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: WELCOME HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-5 sm:px-6 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <!-- Icon Pendidik (Amber Theme) -->
                    <div class="flex-shrink-0 w-11 h-11 bg-amber-500 flex items-center justify-center rounded-lg shadow-lg shadow-amber-200/50">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Selamat Datang, {{ Auth::user()->name }}</h2>
                        <p class="text-xs text-slate-500 font-medium">
                            <span class="text-emerald-600 font-bold inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Sistem Optimal
                            </span> 
                            • Ruang Kendali Pendidik Hadirify
                        </p>
                    </div>
                </div>

                <!-- Realtime Clock & Date (Seragam dengan Admin) -->
                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50/80 rounded-xl border border-slate-100">
                    <div class="text-right">
                        <p id="realtime-date" class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                            {{ now()->translatedFormat('d F Y') }}
                        </p>
                        <p id="realtime-clock" class="text-sm font-bold text-[#0b1e36] font-mono leading-none">
                            {{ date('H:i:s') }}
                        </p>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-200 mx-1"></div>
                    <i data-lucide="calendar-range" class="w-5 h-5 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: SCROLLABLE CONTENT ================= -->
        <div class="flex-1 min-h-0 space-y-6 overflow-y-auto no-scrollbar pb-10">
            
            <!-- Attendance Stats (Left Accent Line Style) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $guruStats = [
                        ['label' => 'Siswa Hadir', 'val' => $stats['hadir'], 'icon' => 'check-circle', 'color' => 'bg-emerald-500'],
                        ['label' => 'Izin', 'val' => $stats['izin'], 'icon' => 'user-check', 'color' => 'bg-sky-500'],
                        ['label' => 'Sakit', 'val' => $stats['sakit'], 'icon' => 'clipboard-list', 'color' => 'bg-amber-500'],
                        ['label' => 'Alpa', 'val' => $stats['alpa'], 'icon' => 'alert-circle', 'color' => 'bg-rose-500'],
                    ];
                @endphp
                
                @foreach($guruStats as $s)
                <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] {{ $s['color'] }}"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $s['label'] }}</p>
                            <div class="text-2xl font-bold text-[#0b1e36] tracking-tighter leading-none">{{ $s['val'] }} <span class="text-[10px] text-slate-300 font-medium">Siswa</span></div>
                        </div>
                        <div class="p-1.5 bg-slate-50 text-slate-300 group-hover:text-slate-900 transition-colors">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Quick Access (Professional Module Style) -->
            <div class="space-y-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-8 h-[1px] bg-slate-200"></span>
                    Kendali Presensi Kelas
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Presensi Manual -->
                    <a href="/guru/manual" class="group p-5 bg-white border border-slate-200/70 rounded-xl hover:shadow-xl hover:shadow-slate-200/30 hover:border-slate-300 transition-all flex items-center gap-5">
                        <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-lg flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition-all duration-300">
                            <i data-lucide="edit-3" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800 tracking-tight leading-none mb-1.5">Presensi Manual</h4>
                            <p class="text-[11px] text-slate-400 font-medium leading-relaxed">Input data kehadiran tatap muka langsung di kelas.</p>
                        </div>
                    </a>

                    <!-- QR Code -->
                    <a href="/guru/qr" class="group p-5 bg-white border border-slate-200/70 rounded-xl hover:shadow-xl hover:shadow-slate-200/30 hover:border-slate-300 transition-all flex items-center gap-5">
                        <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                            <i data-lucide="qr-code" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800 tracking-tight leading-none mb-1.5">Rilis QR Code</h4>
                            <p class="text-[11px] text-slate-400 font-medium leading-relaxed">Buka gerbang pemindaian mandiri bagi siswa kelas.</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Tambahan Modul Laporan (Opsi) -->
            <div class="space-y-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                    <span class="w-8 h-[1px] bg-slate-200"></span>
                    Analitik & Laporan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="/guru/rekap" class="p-4 bg-white border border-slate-200/60 rounded-xl hover:bg-slate-50 transition-all flex items-center gap-3 group">
                        <i data-lucide="file-text" class="w-4 h-4 text-slate-400 group-hover:text-amber-500"></i>
                        <span class="text-xs font-bold text-slate-600">Rekap Presensi</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Script Jam Realtime (Copy dari Admin) -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeStr = `${hours}:${minutes}:${seconds}`;
            
            const clockElement = document.getElementById('realtime-clock');
            if(clockElement) clockElement.textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guru-layout>