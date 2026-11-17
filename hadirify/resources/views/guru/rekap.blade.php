<x-guru-layout>
    <div class="animate-in fade-in duration-300 space-y-6">
        
        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-lg border border-slate-200/80 shadow-2xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-[#0b1e36]/10 text-[#0b1e36] rounded-md">
                        <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Rekap & Laporan Presensi</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Analisis kehadiran siswa dan ekspor data laporan bulanan</p>
            </div>
            
            <!-- Tombol Ekspor Fungsional -->
            <div class="flex items-center gap-2">
                <a href="{{ route('guru.rekap.cetak', ['kelas_id' => request('kelas_id'), 'bulan' => request('bulan', now()->month)]) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-bold rounded-md shadow-2xs transition-all">
                    <i data-lucide="file-text" class="w-4 h-4 text-rose-500"></i>
                    PDF Laporan
                </a>
                <a href="{{ route('guru.rekap.excel', ['kelas_id' => request('kelas_id'), 'bulan' => request('bulan', now()->month)]) }}" class="flex items-center gap-2 px-4 py-2 bg-[#10b981] hover:bg-[#0d9488] text-white text-xs font-bold rounded-md shadow-2xs transition-all">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Ekspor Excel
                </a>
            </div>
        </div>

        <!-- Form Filter -->
        <div class="bg-white p-6 rounded-lg border border-slate-200/80 shadow-2xs">
            <form action="{{ route('guru.rekap') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Pilih Kelas</label>
                    <div class="relative">
                        <select name="kelas_id" required class="w-full pl-4 pr-10 py-2.5 rounded-md border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] bg-white transition-all duration-200 cursor-pointer">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} ({{ $k->tahun_ajaran }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Pilih Bulan</label>
                    <div class="relative">
                        <select name="bulan" required class="w-full pl-4 pr-10 py-2.5 rounded-md border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] bg-white transition-all duration-200 cursor-pointer">
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
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full py-2.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-[13.5px] font-bold rounded-md shadow-2xs transition-all flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        Cari & Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Hasil -->
        <div class="bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0b1e36] flex items-center gap-2">
                    <i data-lucide="table" class="w-4 h-4 text-sky-500"></i>
                    Tabel Rekapitulasi Presensi Siswa
                </h3>
                @if(request('kelas_id'))
                    <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-[10px] font-bold rounded-md uppercase tracking-wider border border-sky-100">
                        Bulan: {{ $months[request('bulan', now()->month)] }}
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-3.5 w-16 text-center">No</th>
                            <th class="px-6 py-3.5">Nama Siswa</th>
                            <th class="px-6 py-3.5 text-center">Hadir</th>
                            <th class="px-6 py-3.5 text-center">Sakit</th>
                            <th class="px-6 py-3.5 text-center">Izin</th>
                            <th class="px-6 py-3.5 text-center">Alpa</th>
                            <th class="px-6 py-3.5 text-center">Persentase Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13.5px]">
                        @forelse($rekaps ?? [] as $index => $rekap)
                            @php
                                $total_hari = $rekap->hadir + $rekap->sakit + $rekap->izin + $rekap->alpa;
                                $persentase = $total_hari > 0 ? round(($rekap->hadir / $total_hari) * 100) : 0;
                                
                                // Gradasi status warna
                                if ($persentase >= 90) {
                                    $bgClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                                } elseif ($persentase >= 75) {
                                    $bgClass = 'bg-amber-50 text-amber-600 border-amber-100';
                                } else {
                                    $bgClass = 'bg-rose-50 text-rose-600 border-rose-100';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-slate-400 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-3.5 font-bold text-[#0b1e36]">
                                    <div class="flex flex-col">
                                        <span>{{ $rekap->siswa->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono tracking-tight">{{ $rekap->siswa->nisn ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-center font-semibold text-slate-600">{{ $rekap->hadir }} Hari</td>
                                <td class="px-6 py-3.5 text-center font-semibold text-amber-600 bg-amber-50/20">{{ $rekap->sakit }} Hari</td>
                                <td class="px-6 py-3.5 text-center font-semibold text-sky-600 bg-sky-50/20">{{ $rekap->izin }} Hari</td>
                                <td class="px-6 py-3.5 text-center font-semibold text-rose-600 bg-rose-50/20">{{ $rekap->alpa }} Hari</td>
                                <td class="px-6 py-3.5 text-center">
                                    <span class="inline-block px-2.5 py-1 text-xs font-bold rounded-md border {{ $bgClass }} font-mono">
                                        {{ $persentase }}%
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 bg-slate-50 rounded-md border border-slate-100 text-slate-300">
                                            <i data-lucide="folder-search" class="w-10 h-10"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#0b1e36] text-sm">Belum Ada Data Rekap</h4>
                                            <p class="text-xs text-slate-400 mt-1 max-w-[280px]">Pilih kelas dan bulan di atas terlebih dahulu untuk memuat data presensi.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-guru-layout>