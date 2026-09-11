<x-admin-layout>
    <!-- Container Utama: Mengalir secara natural tanpa batasan tinggi kaku -->
    <div class="animate-in fade-in duration-500 space-y-6 pb-12">
        
        <!-- ================= SECTION 1: HEADER & SEARCH ================= -->
        <div class="space-y-4">
            
            <!-- Header Page -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/50 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-[#0b1e36] text-white rounded-lg flex items-center justify-center shadow-lg shrink-0">
                    <i data-lucide="edit-3" class="w-5 h-5 text-white"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Koreksi Absensi</h2>
                    <p class="text-xs text-slate-500 font-medium">Validasi dan pembetulan status kehadiran manual.</p>
                </div>
            </div>

            <!-- Formulir Pencarian -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                <form action="{{ route('admin.koreksi') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full space-y-1.5">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider ml-1">Cari Nama / NISN Siswa</label>
                        <div class="relative group">
                            <input type="text" name="search" placeholder="Masukkan Nama atau NISN..." value="{{ request('search') }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0b1e36] focus:bg-white outline-none text-sm font-semibold text-slate-700 transition-all">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#0b1e36] transition-colors">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full md:w-auto h-12 px-8 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-md transition-all flex items-center justify-center gap-2.5 active:scale-[0.98] cursor-pointer">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        Muat Riwayat
                    </button>
                </form>
            </div>
        </div>

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-3 shadow-sm animate-in zoom-in duration-300">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 2: AREA HASIL TABEL (NATURAL FLOW) ================= -->
        <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden">
            
            <!-- Header Info Tabel -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-bold text-[#0b1e36] uppercase tracking-wider flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4 text-sky-500"></i>
                    Dataset Riwayat Absensi
                </h3>
                @if(request('search'))
                    <span class="px-2.5 py-1 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg shadow-2xs">
                        Hasil: "{{ request('search') }}"
                    </span>
                @endif
            </div>

            <!-- Tabel Mengalir Sesuai Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-100 text-xs font-semibold text-slate-500">
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Kelas & Mapel</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Koreksi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($absensis ?? [] as $absen)
                            @php $student = $absen->user ?? $absen->siswa; @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-xs">{{ $student->name ?? '---' }}</span>
                                        <span class="text-[10px] font-mono text-slate-400 font-medium">{{ $student->nisn ?? 'NISN N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[10px] font-bold rounded border border-slate-200">{{ $student->kelas->nama_kelas ?? 'N/A' }}</span>
                                            <span class="text-xs font-bold text-slate-700">{{ $absen->jadwal->mapel->nama_mapel ?? 'Mapel' }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @php
                                        $badges = [
                                            'H' => ['bg-emerald-50', 'text-emerald-700', 'border-emerald-200/60', 'L' => 'Hadir'],
                                            'S' => ['bg-amber-50', 'text-amber-700', 'border-amber-200/60', 'L' => 'Sakit'],
                                            'I' => ['bg-sky-50', 'text-sky-700', 'border-sky-200/60', 'L' => 'Izin'],
                                            'A' => ['bg-rose-50', 'text-rose-700', 'border-rose-200/60', 'L' => 'Alpa'],
                                        ];
                                        $b = $badges[$absen->status] ?? ['bg-slate-50', 'text-slate-500', 'border-slate-200', 'L' => 'N/A'];
                                    @endphp
                                    <span class="px-2.5 py-1 {{ $b[0] }} {{ $b[1] }} {{ $b[2] }} text-xs font-semibold rounded-full border">
                                        {{ $b['L'] }}
                                    </span>
                                </td>
                                
                                <form action="{{ route('admin.koreksi.update', $absen->id) }}" method="POST" class="m-0">
                                    @csrf @method('PUT')
                                    <td class="px-6 py-3.5 text-center">
                                        <div class="relative inline-block w-full max-w-[110px]">
                                            <select name="status" class="w-full pl-3 pr-7 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 outline-none appearance-none cursor-pointer focus:bg-white focus:border-[#0b1e36]">
                                                <option value="H" {{ $absen->status == 'H' ? 'selected' : '' }}>Hadir</option>
                                                <option value="S" {{ $absen->status == 'S' ? 'selected' : '' }}>Sakit</option>
                                                <option value="I" {{ $absen->status == 'I' ? 'selected' : '' }}>Izin</option>
                                                <option value="A" {{ $absen->status == 'A' ? 'selected' : '' }}>Alpa</option>
                                            </select>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 text-right">
                                        <button type="submit" class="px-3.5 py-1.5 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-bold rounded-lg shadow-sm active:scale-95 transition-all cursor-pointer">
                                            Update
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="search-x" class="w-10 h-10 mb-2 opacity-40"></i>
                                        <p class="text-xs font-bold">Data Tidak Ditemukan</p>
                                        <p class="text-xs text-slate-400 mt-1">Cari Nama/NISN untuk memuat riwayat presensi</p>
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