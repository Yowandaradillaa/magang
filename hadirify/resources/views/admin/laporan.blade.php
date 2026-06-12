<x-admin-layout>
    <div class="animate-in fade-in duration-300 space-y-8">
        
        <!-- Header Page -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-rose-500/10 text-rose-600 rounded-lg">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Laporan Kehadiran Sekolah</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Statistik kehadiran bulanan secara agregat per kelas di sekolah.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="#" onclick="alert('Fitur cetak PDF laporan sekolah sedang disiapkan.')" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-bold rounded-xl shadow-sm transition-all">
                    <i data-lucide="file-text" class="w-4 h-4 text-rose-500"></i>
                    PDF
                </a>
                <a href="#" onclick="alert('Fitur ekspor excel laporan sekolah sedang disiapkan.')" class="flex items-center gap-2 px-4 py-2.5 bg-[#10b981] hover:bg-[#0d9488] text-white text-xs font-bold rounded-xl shadow shadow-sm transition-all">
                    <i data-lucide="sheet" class="w-4 h-4"></i>
                    Excel
                </a>
            </div>
        </div>

        <!-- Form Filter -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
            <form action="{{ route('admin.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Periode Waktu</label>
                    <div class="relative">
                        <select name="bulan" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] appearance-none bg-white transition-all duration-200">
                            @php
                                $months = [
                                    '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', 
                                    '4' => 'April', '5' => 'Mei', '6' => 'Juni', 
                                    '7' => 'Juli', '8' => 'Agustus', '9' => 'September', 
                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ];
                                $currentMonth = request('bulan', now()->month);
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ $currentMonth == $num ? 'selected' : '' }}>
                                    {{ $name }} {{ now()->year }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Filter Kelas (Opsional)</label>
                    <div class="relative">
                        <select name="kelas_id" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] appearance-none bg-white transition-all duration-200">
                            <option value="">Semua Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full py-3 bg-[#0b1e36] hover:bg-[#112d52] text-white text-[13.5px] font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Filter Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Grid Statistik Kehadiran -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#0b1e36]"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-[#0b1e36] font-mono">{{ $stats['rata_kehadiran'] ?? 0 }}%</div>
                    <div class="p-2 bg-[#0b1e36]/5 text-[#0b1e36] rounded-xl">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Rata-rata Kehadiran</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-emerald-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-emerald-500 font-mono">{{ number_format($stats['total_hadir'] ?? 0) }}</div>
                    <div class="p-2 bg-emerald-50/10 text-emerald-600 rounded-xl">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Total Hadir (Bulan Ini)</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-amber-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-amber-500 font-mono">{{ number_format($stats['total_izin_sakit'] ?? 0) }}</div>
                    <div class="p-2 bg-amber-50/10 text-amber-600 rounded-xl">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Total Izin & Sakit</div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-rose-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-rose-500 font-mono">{{ number_format($stats['total_alpa'] ?? 0) }}</div>
                    <div class="p-2 bg-rose-50/10 text-rose-600 rounded-xl">
                        <i data-lucide="x-circle" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Total Alpa (Tanpa Ket)</div>
            </div>
        </div>

        <!-- Tabel Rekap Kelas -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0b1e36] flex items-center gap-2">
                    <i data-lucide="table" class="w-4.5 h-4.5 text-rose-500"></i>
                    Rekapitulasi Kehadiran per Kelas
                </h3>
                <span class="px-3 py-1 bg-[#0b1e36]/5 text-[#0b1e36] text-[10px] font-bold rounded-full uppercase tracking-wider border border-[#0b1e36]/10">
                    Bulan: {{ $months[request('bulan', now()->month)] }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4 text-center">Hadir</th>
                            <th class="px-6 py-4 text-center">Sakit</th>
                            <th class="px-6 py-4 text-center">Izin</th>
                            <th class="px-6 py-4 text-center">Alpa</th>
                            <th class="px-6 py-4 text-center font-mono">Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13.5px]">
                        @forelse($laporans ?? [] as $laporan)
                            @php
                                $total_hari = ($laporan->hadir ?? 0) + ($laporan->sakit ?? 0) + ($laporan->izin ?? 0) + ($laporan->alpa ?? 0);
                                $persentase = $total_hari > 0 ? round((($laporan->hadir ?? 0) / $total_hari) * 100) : 0;
                                
                                if ($persentase >= 90) {
                                    $warnaClass = 'text-emerald-600 bg-emerald-50 border-emerald-100';
                                } elseif ($persentase >= 75) {
                                    $warnaClass = 'text-amber-600 bg-amber-50 border-amber-100';
                                } else {
                                    $warnaClass = 'text-rose-600 bg-rose-50 border-rose-100';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#0b1e36]">{{ $laporan->kelas->nama_kelas ?? 'Kelas' }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-600">{{ $laporan->hadir ?? 0 }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-amber-600">{{ $laporan->sakit ?? 0 }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-sky-600">{{ $laporan->izin ?? 0 }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-rose-600">{{ $laporan->alpa ?? 0 }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-3 py-1 text-xs font-bold rounded-full border {{ $warnaClass }} font-mono">
                                        {{ $persentase }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                    Belum ada data laporan absensi kelas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>