<x-app-layout>
    <!-- Container Utama: Fixed Height agar Header diam & Tabel bisa di-scroll -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED - KOTAK ELEGAN) ================= -->
        <div class="flex-none bg-white border border-slate-200/70 rounded-lg shadow-2xs">
            <div class="p-4 sm:px-6 sm:py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <!-- Icon History (Navy Theme) -->
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0b1e36] flex items-center justify-center rounded-md shadow-sm">
                        <i data-lucide="history" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-base font-extrabold text-[#0b1e36] tracking-tight">Rekap Kehadiran</h2>
                        <p class="text-[11px] text-slate-500 font-medium">Log absensi digital transparan & akurat</p>
                    </div>
                </div>

                <!-- Clock & Date (Konsisten dengan Dashboard) -->
                <div class="flex items-center gap-3 px-3.5 py-1.5 bg-slate-50/80 rounded-md border border-slate-200/70">
                    <div class="text-right">
                        <p id="realtime-date" class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5">
                            {{ now()->translatedFormat('d F Y') }}
                        </p>
                        <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">
                            00:00:00
                        </p>
                    </div>
                    <div class="w-[1px] h-5 bg-slate-200 mx-0.5"></div>
                    <i data-lucide="calendar-days" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: AREA TABEL (SCROLLABLE - KOTAK TEGAS) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-lg border border-slate-200/70 shadow-2xs overflow-hidden flex flex-col">
            
            <!-- Table Info Bar & Quick Filter Tabs -->
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/40">
                <div class="flex items-center gap-2.5">
                    <h3 class="text-xs font-bold text-[#0b1e36] uppercase tracking-wider flex items-center gap-2">
                        <i data-lucide="database" class="w-4 h-4 text-sky-600"></i>
                        Dataset Absensi Anda
                    </h3>
                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-500 text-[10px] font-bold rounded-md shadow-2xs">
                        {{ $history->total() }} Entri
                    </span>
                </div>

                <!-- Quick Filter Tabs -->
                <div class="flex items-center gap-1 p-1 bg-slate-200/60 rounded-xl overflow-x-auto no-scrollbar">
                    @php $p = $periode ?? 'semua'; @endphp
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'semua', 'page' => 1]) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $p === 'semua' ? 'bg-white text-[#0b1e36] shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'hari_ini', 'page' => 1]) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $p === 'hari_ini' ? 'bg-white text-[#0b1e36] shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Hari Ini
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'minggu_ini', 'page' => 1]) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $p === 'minggu_ini' ? 'bg-white text-[#0b1e36] shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['periode' => 'bulan_ini', 'page' => 1]) }}"
                       class="px-3 py-1 rounded-lg text-xs font-bold transition-all whitespace-nowrap {{ $p === 'bulan_ini' ? 'bg-white text-[#0b1e36] shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        Bulan Ini
                    </a>
                </div>
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

            <!-- Pagination Bar -->
            @if($history->hasPages())
                <div class="px-6 py-3.5 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/40">
                    <p class="text-xs text-slate-500 font-medium">
                        Menampilkan <span class="font-bold text-slate-700">{{ $history->firstItem() ?? 0 }}</span> - <span class="font-bold text-slate-700">{{ $history->lastItem() ?? 0 }}</span> dari <span class="font-bold text-slate-700">{{ $history->total() }}</span> entri
                    </p>
                    <div class="flex items-center gap-1.5">
                        @if ($history->onFirstPage())
                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 text-xs font-bold cursor-not-allowed">
                                &larr; Prev
                            </span>
                        @else
                            <a href="{{ $history->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all shadow-2xs">
                                &larr; Prev
                            </a>
                        @endif

                        <span class="px-3.5 py-1.5 rounded-lg bg-[#0b1e36] text-white text-xs font-black shadow-xs">
                            {{ $history->currentPage() }} / {{ $history->lastPage() }}
                        </span>

                        @if ($history->hasMorePages())
                            <a href="{{ $history->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all shadow-2xs">
                                Next &rarr;
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 text-xs font-bold cursor-not-allowed">
                                Next &rarr;
                            </span>
                        @endif
                    </div>
                </div>
            @endif
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