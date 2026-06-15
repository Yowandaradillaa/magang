<x-admin-layout>
    <!-- Container Utama -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white p-5 rounded-xl border border-slate-200/50 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-slate-900 text-white rounded-lg flex items-center justify-center shadow-lg">
                    <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">Laporan Kehadiran</h2>
                    <p class="text-[11px] text-slate-500 font-medium italic">Statistik Agregat Sekolah & Per Kelas</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button onclick="alert('Fitur PDF sedang disiapkan')" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-50 transition-all">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-rose-500"></i> Export PDF
                </button>
                <button onclick="alert('Fitur Excel sedang disiapkan')" class="flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-all">
                    <i data-lucide="sheet" class="w-3.5 h-3.5"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- ================= SECTION 2: CONTENT (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 space-y-4 overflow-y-auto no-scrollbar pb-10">
            
            @php
                // Mendefinisikan variabel yang hilang agar tidak error
                $months = [
                    '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', 
                    '4' => 'April', '5' => 'Mei', '6' => 'Juni', 
                    '7' => 'Juli', '8' => 'Agustus', '9' => 'September', 
                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                $currentMonth = request('bulan', now()->month);
            @endphp

            <!-- FORM FILTER -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                <form action="{{ route('admin.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <div class="md:col-span-4 space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Periode Bulan</label>
                        <div class="relative group">
                            <select name="bulan" required class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-slate-900 appearance-none transition-all">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>
                                        {{ $name }} {{ now()->year }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-4 space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Filter Kelas</label>
                        <div class="relative group">
                            <select name="kelas_id" class="w-full pl-4 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-slate-900 appearance-none transition-all">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="md:col-span-4">
                        <button type="submit" class="w-full h-12 bg-slate-900 hover:bg-black text-white text-[11px] font-black uppercase tracking-[0.15em] rounded-xl flex items-center justify-center gap-3 transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                            <i data-lucide="search" class="w-5 h-5 stroke-[2.5px]"></i>
                            Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- STATS GRID -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $statItems = [
                        ['label' => 'Rata-rata Presensi', 'val' => ($stats['rata_kehadiran'] ?? 0) . '%', 'icon' => 'trending-up', 'color' => 'bg-slate-900'],
                        ['label' => 'Total Hadir', 'val' => number_format($stats['total_hadir'] ?? 0), 'icon' => 'check-circle', 'color' => 'bg-emerald-500'],
                        ['label' => 'Total Izin/Sakit', 'val' => number_format($stats['total_izin_sakit'] ?? 0), 'icon' => 'clipboard-list', 'color' => 'bg-amber-500'],
                        ['label' => 'Total Alpa', 'val' => number_format($stats['total_alpa'] ?? 0), 'icon' => 'alert-circle', 'color' => 'bg-rose-500'],
                    ];
                @endphp
                @foreach($statItems as $s)
                <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:border-slate-300 transition-all">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] {{ $s['color'] }}"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $s['label'] }}</p>
                            <div class="text-xl font-bold text-slate-900 tracking-tight leading-none">{{ $s['val'] }}</div>
                        </div>
                        <div class="p-2 bg-slate-50 text-slate-300 group-hover:text-slate-900 transition-colors">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- DATA TABLE -->
            <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                    <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest leading-none">Rekap Kehadiran Per Kelas</h3>
                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-400 text-[9px] font-black rounded uppercase leading-none">
                        Bulan: {{ $months[$currentMonth] }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white border-b border-slate-50">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kelas</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Hadir</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Sakit</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Izin</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Alpa</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-[13px]">
                            @forelse($laporans ?? [] as $laporan)
                                @php
                                    $total_hari = ($laporan->hadir ?? 0) + ($laporan->sakit ?? 0) + ($laporan->izin ?? 0) + ($laporan->alpa ?? 0);
                                    $persentase = $total_hari > 0 ? round((($laporan->hadir ?? 0) / $total_hari) * 100) : 0;
                                    
                                    if ($persentase >= 90) { $warnaClass = 'text-emerald-600 bg-emerald-50 border-emerald-100'; }
                                    elseif ($persentase >= 75) { $warnaClass = 'text-amber-600 bg-amber-50 border-amber-100'; }
                                    else { $warnaClass = 'text-rose-600 bg-rose-50 border-rose-100'; }
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800 tracking-tight">{{ $laporan->kelas->nama_kelas ?? 'N/A' }}</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-slate-600">{{ $laporan->hadir ?? 0 }}</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-amber-500">{{ $laporan->sakit ?? 0 }}</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-sky-500">{{ $laporan->izin ?? 0 }}</td>
                                    <td class="px-6 py-3.5 text-center font-bold text-rose-500">{{ $laporan->alpa ?? 0 }}</td>
                                    <td class="px-6 py-3.5 text-center">
                                        <span class="inline-block px-3 py-1 text-[10px] font-black rounded border {{ $warnaClass }} font-mono">
                                            {{ $persentase }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-20 text-center opacity-30">
                                        <p class="text-[10px] font-black uppercase tracking-widest italic">Data Tidak Ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-admin-layout>