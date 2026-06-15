<x-admin-layout>
    <!-- Container Utama: Tinggi tetap agar header diam & tabel bisa di-scroll -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER & SEARCH (FIXED) ================= -->
        <div class="flex-none space-y-4">
            
            <!-- Header Page -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/50 shadow-sm flex items-center gap-4">
                <div class="w-10 h-10 bg-[#0b1e36] text-white rounded-lg flex items-center justify-center shadow-lg">
                    <i data-lucide="edit-3" class="w-5 h-5 text-white"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Koreksi Absensi</h2>
                    <p class="text-[11px] text-slate-500 font-medium italic">Validasi dan pembetulan status kehadiran manual.</p>
                </div>
            </div>

            <!-- Formulir Pencarian (Sleek Design) -->
            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm">
                <form action="{{ route('admin.koreksi') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 w-full space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Cari Nama / NISN Siswa</label>
                        <div class="relative group">
                            <input type="text" name="search" placeholder="Masukkan Nama atau NISN..." value="{{ request('search') }}" required
                                   class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-[#0b1e36] focus:bg-white outline-none text-sm font-semibold text-slate-700 transition-all">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-[#0b1e36] transition-colors">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="w-full md:w-auto h-12 px-8 bg-[#0b1e36] hover:bg-black text-white text-[11px] font-black uppercase tracking-[0.15em] rounded-xl shadow-lg shadow-slate-200 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                        <i data-lucide="filter" class="w-5 h-5 stroke-[2.5px]"></i>
                        Muat Riwayat
                    </button>
                </form>
            </div>
        </div>

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-3 shadow-sm animate-in zoom-in duration-300">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 2: AREA HASIL TABEL (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
            
            <!-- Header Info Tabel -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                <h3 class="text-[11px] font-black text-[#0b1e36] uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="database" class="w-4 h-4 text-rose-500"></i>
                    Dataset Riwayat Absensi
                </h3>
                @if(request('search'))
                    <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-400 text-[9px] font-black rounded uppercase">
                        Hasil: "{{ request('search') }}"
                    </span>
                @endif
            </div>

            <!-- Pembungkus Scroll Vertikal -->
            <div class="flex-1 overflow-y-auto no-scrollbar relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-sm">
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Kelas & Mapel</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Koreksi</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-[13px]">
                        @forelse($absensis ?? [] as $absen)
                            @php $student = $absen->user ?? $absen->siswa; @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 text-xs">{{ $student->name ?? '---' }}</span>
                                        <span class="text-[9px] font-mono text-slate-400 font-bold uppercase">{{ $student->nisn ?? 'NISN N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="px-1.5 py-0.5 bg-slate-100 text-slate-600 text-[8px] font-black rounded border border-slate-200 uppercase">{{ $student->kelas->nama_kelas ?? 'N/A' }}</span>
                                            <span class="text-[10px] font-bold text-slate-700">{{ $absen->jadwal->mapel->nama_mapel ?? 'Mapel' }}</span>
                                        </div>
                                        <span class="text-[9px] text-slate-400 font-bold">{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @php
                                        $badges = [
                                            'H' => ['bg-emerald-50', 'text-emerald-600', 'border-emerald-100', 'L' => 'Hadir'],
                                            'S' => ['bg-amber-50', 'text-amber-600', 'border-amber-100', 'L' => 'Sakit'],
                                            'I' => ['bg-sky-50', 'text-sky-600', 'border-sky-100', 'L' => 'Izin'],
                                            'A' => ['bg-rose-50', 'text-rose-600', 'border-rose-100', 'L' => 'Alpa'],
                                        ];
                                        $b = $badges[$absen->status] ?? ['bg-slate-50', 'text-slate-400', 'border-slate-100', 'L' => 'N/A'];
                                    @endphp
                                    <span class="px-2.5 py-0.5 {{ $b[0] }} {{ $b[1] }} {{ $b[2] }} text-[8px] font-black rounded border border-emerald-100 uppercase tracking-wider">
                                        {{ $b['L'] }}
                                    </span>
                                </td>
                                
                                <form action="{{ route('admin.koreksi.update', $absen->id) }}" method="POST" class="m-0">
                                    @csrf @method('PUT')
                                    <td class="px-6 py-3 text-center">
                                        <div class="relative inline-block w-full max-w-[100px]">
                                            <select name="status" class="w-full pl-2 pr-6 py-1 bg-slate-50 border border-slate-200 rounded-md text-[10px] font-bold text-slate-700 outline-none appearance-none cursor-pointer">
                                                <option value="H" {{ $absen->status == 'H' ? 'selected' : '' }}>Hadir</option>
                                                <option value="S" {{ $absen->status == 'S' ? 'selected' : '' }}>Sakit</option>
                                                <option value="I" {{ $absen->status == 'I' ? 'selected' : '' }}>Izin</option>
                                                <option value="A" {{ $absen->status == 'A' ? 'selected' : '' }}>Alpa</option>
                                            </select>
                                            <i data-lucide="chevron-down" class="w-3 h-3 absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <button type="submit" class="px-3 py-1 bg-slate-900 hover:bg-black text-white text-[9px] font-black rounded uppercase shadow-sm active:scale-95 transition-all">
                                            Update
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center opacity-30">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="search-x" class="w-10 h-10 mb-2"></i>
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em]">Data Tidak Ditemukan</p>
                                        <p class="text-[9px] mt-1 italic">Cari Nama/NISN untuk memuat riwayat</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-admin-layout>