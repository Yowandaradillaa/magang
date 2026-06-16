<x-app-layout>
    <!-- Container Utama: Fixed Height agar Header diam & Tabel bisa di-scroll -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-5 sm:px-6 sm:py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <!-- Icon History (Navy Theme) -->
                    <div class="flex-shrink-0 w-11 h-11 bg-[#0b1e36] flex items-center justify-center rounded-lg shadow-lg">
                        <i data-lucide="history" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Rekap Kehadiran</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">Log absensi digital transparan & akurat</p>
                    </div>
                </div>

                <!-- Clock & Date (Konsisten dengan Dashboard) -->
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
                    <i data-lucide="calendar-days" class="w-5 h-5 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: AREA TABEL (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Table Info Bar -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h3 class="text-[11px] font-black text-[#0b1e36] uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4 text-sky-600"></i>
                    Dataset Absensi Anda
                </h3>
                <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-400 text-[9px] font-black rounded uppercase">
                    Total: {{ $history->count() }} Entri
                </span>
            </div>

            <!-- Scrollable Table Body -->
            <div class="flex-1 overflow-y-auto no-scrollbar relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-sm">
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Mata Pelajaran</th>
                            <th class="px-6 py-4 text-center">Waktu</th>
                            <th class="px-6 py-4 text-center">Metode</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($history as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 text-xs tracking-tight">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-[#0b1e36] text-xs uppercase tracking-tight">
                                        {{ $item->jadwal->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium italic">
                                        Oleh: {{ $item->jadwal->guru->name ?? 'Pengajar' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-mono text-[11px] font-bold text-slate-600">
                                    {{ $item->waktu_absen ? \Carbon\Carbon::parse($item->waktu_absen)->format('H:i') : '--:--' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black rounded border border-slate-200 uppercase">
                                    {{ $item->metode ?? 'Sistem' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $statusConfig = [
                                        'H' => ['label' => 'Hadir', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                        'I' => ['label' => 'Izin', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                                        'S' => ['label' => 'Sakit', 'class' => 'bg-sky-50 text-sky-600 border-sky-100'],
                                        'A' => ['label' => 'Alpa', 'class' => 'bg-rose-50 text-rose-600 border-rose-100'],
                                    ];
                                    $config = $statusConfig[$item->status] ?? ['label' => 'N/A', 'class' => 'bg-slate-50 text-slate-400 border-slate-200'];
                                @endphp
                                <span class="inline-block px-3 py-1 text-[10px] font-black rounded border {{ $config['class'] }} uppercase tracking-tighter">
                                    {{ $config['label'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center opacity-30">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="inbox" class="w-12 h-12 mb-3 text-slate-300"></i>
                                    <p class="text-[10px] font-black uppercase tracking-[0.2em]">Data Absensi Kosong</p>
                                    <p class="text-[11px] mt-1 italic">Anda belum memiliki riwayat kehadiran di sistem</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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