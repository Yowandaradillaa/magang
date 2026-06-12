<x-admin-layout>
    <div class="animate-in fade-in duration-300 space-y-8">
        
        <!-- Header Page -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-rose-500/10 text-rose-600 rounded-lg">
                        <i data-lucide="edit" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Koreksi Absensi Siswa</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Melakukan pembaruan atau pembetulan status kehadiran siswa secara manual.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold rounded-xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 12 12 14 14"></polyline><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Formulir Pencarian -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
            <form action="{{ route('admin.koreksi') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                <div class="md:col-span-9 space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Nama / NISN Siswa</label>
                    <div class="relative">
                        <input type="text" name="search" placeholder="Ketik nama atau nomor induk siswa (NISN)..." value="{{ request('search') }}" required
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] placeholder-slate-300 transition-all duration-200">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <button type="submit" class="w-full py-3 bg-[#0b1e36] hover:bg-[#112d52] text-white text-[13.5px] font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Cari Riwayat
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Hasil Pencarian -->
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#0b1e36] flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-4.5 h-4.5 text-rose-500"></i>
                    Data Hasil Pencarian Absensi
                </h3>
                @if(request('search'))
                    <span class="px-3 py-1 bg-rose-500/10 text-rose-600 text-[10px] font-bold rounded-full uppercase tracking-wider border border-rose-500/20">
                        Kata Kunci: "{{ request('search') }}"
                    </span>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/60 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Kelas & Mapel</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Status Saat Ini</th>
                            <th class="px-6 py-4 text-center">Koreksi Menjadi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-[13px]">
                        @forelse($absensis ?? [] as $absen)
                            @php
                                $student = $absen->user ?? $absen->siswa;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-[#0b1e36]">
                                    <div class="flex flex-col">
                                        <span>{{ $student->name ?? 'Nama Siswa' }}</span>
                                        <span class="text-[10px] font-mono text-slate-400 font-bold mt-0.5">{{ $student->nisn ?? 'NISN' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    <div class="flex flex-col">
                                        <span>{{ $student->kelas->nama_kelas ?? 'Tanpa Kelas' }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold mt-0.5">{{ $absen->jadwal->mapel->nama_mapel ?? 'Mata Pelajaran' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[#0b1e36] font-semibold">
                                    {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($absen->status == 'H')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100 uppercase tracking-wider">Hadir</span>
                                    @elseif($absen->status == 'S')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-[10px] font-black rounded-full border border-amber-100 uppercase tracking-wider">Sakit</span>
                                    @elseif($absen->status == 'I')
                                        <span class="px-2.5 py-1 bg-sky-50 text-sky-600 text-[10px] font-black rounded-full border border-sky-100 uppercase tracking-wider">Izin</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-[10px] font-black rounded-full border border-rose-100 uppercase tracking-wider">Alpa</span>
                                    @endif
                                </td>
                                
                                <form action="{{ route('admin.koreksi.update', $absen->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PUT')
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block w-32">
                                            <select name="status" class="w-full pl-3 pr-8 py-1.5 border border-slate-200 rounded-lg outline-none focus:border-[#0b1e36] text-xs font-bold text-[#0b1e36] appearance-none bg-white">
                                                <option value="H" {{ $absen->status == 'H' ? 'selected' : '' }}>H - Hadir</option>
                                                <option value="S" {{ $absen->status == 'S' ? 'selected' : '' }}>S - Sakit</option>
                                                <option value="I" {{ $absen->status == 'I' ? 'selected' : '' }}>I - Izin</option>
                                                <option value="A" {{ $absen->status == 'A' ? 'selected' : '' }}>A - Alpa</option>
                                            </select>
                                            <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="submit" class="px-3.5 py-1.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-[11px] font-black rounded-lg transition-all shadow-sm">
                                            Update
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 bg-slate-50 rounded-full border border-slate-100 text-slate-300">
                                            <i data-lucide="search-code" class="w-10 h-10"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#0b1e36] text-sm">Belum Ada Riwayat Dimuat</h4>
                                            <p class="text-xs text-slate-400 mt-1 max-w-[280px]">Ketik nama atau NISN siswa pada kolom pencarian di atas untuk memuat riwayat presensi.</p>
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
</x-admin-layout>