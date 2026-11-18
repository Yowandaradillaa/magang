<x-guru-layout>
    <!-- Container Utama: Fixed Height agar Header diam & Tabel bisa di-scroll -->
    <div x-data="{ tab: 'pending' }" 
         class="animate-in fade-in duration-700 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/80 rounded-lg shadow-2xs">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0b1e36] flex items-center justify-center rounded-md shadow-2xs">
                        <i data-lucide="clipboard-check" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Persetujuan Izin</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">Validasi permohonan ketidakhadiran siswa</p>
                    </div>
                </div>

                <!-- Realtime Clock (Konsistensi dengan Dashboard) -->
                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-md border border-slate-100">
                    <div class="text-right">
                        <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">{{ date('H:i:s') }}</p>
                    </div>
                    <div class="w-[1px] h-4 bg-slate-200"></div>
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: TAB NAVIGATION (FIXED) ================= -->
        <div class="flex-none flex items-center gap-1 bg-slate-100 p-1 rounded-md w-full md:w-fit border border-slate-200/80">
            <button @click="tab = 'pending'" 
                    :class="tab === 'pending' ? 'bg-sky-500 text-white shadow-xs font-bold border-transparent' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                    class="flex-1 md:flex-none px-5 py-2 rounded-md text-xs transition-all border flex items-center justify-center gap-2">
                <i data-lucide="clock" class="w-4 h-4"></i> Menunggu
            </button>
            <button @click="tab = 'all'" 
                    :class="tab === 'all' ? 'bg-sky-500 text-white shadow-xs font-bold border-transparent' : 'text-slate-500 hover:text-slate-800 border-transparent'"
                    class="flex-1 md:flex-none px-5 py-2 rounded-md text-xs transition-all border flex items-center justify-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i> Riwayat Izin
            </button>
        </div>

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md font-bold text-xs flex items-center gap-2 animate-in zoom-in duration-300">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 3: CONTENT AREA (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden flex flex-col">
            <div class="flex-1 overflow-y-auto no-scrollbar relative">
                
                <!-- TAB 1: PENDING (Menunggu Persetujuan) -->
                <div x-show="tab === 'pending'" class="animate-in fade-in duration-300">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-2xs">
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-3.5">Siswa</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5">Rentang Tanggal</th>
                                <th class="px-6 py-3.5 text-center">Keputusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($izins ?? [] as $izin)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-slate-800 text-xs">
                                    {{ $izin->siswa->name ?? ($izin->user->name ?? 'User Tidak Dikenal') }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <span class="px-2.5 py-1 bg-sky-50 text-sky-600 text-[10px] font-bold rounded-md border border-sky-100 uppercase">
                                        {{ $izin->jenis }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-col text-[10px] font-mono font-bold text-slate-500">
                                        <span>{{ \Carbon\Carbon::parse($izin->tanggal_mulai)->format('d/m/Y') }}</span>
                                        <span class="text-[9px] opacity-50">s/d</span>
                                        <span>{{ \Carbon\Carbon::parse($izin->tanggal_selesai)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Disetujui">
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-md uppercase shadow-2xs transition-all active:scale-95">Setujui</button>
                                        </form>
                                        <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded-md uppercase shadow-2xs transition-all active:scale-95">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-20 text-center opacity-40">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="inbox" class="w-10 h-10 mb-2 text-slate-400"></i>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Tidak ada permohonan izin</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- TAB 2: HISTORY (Riwayat) -->
                <div x-show="tab === 'all'" style="display: none;" class="animate-in fade-in duration-300">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-2xs">
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-3.5">Siswa</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5 text-center">Status Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($riwayat ?? [] as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-bold text-slate-700 text-xs">
                                    {{ $item->siswa->name ?? ($item->user->name ?? 'User') }}
                                </td>
                                <td class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ $item->jenis }}</td>
                                <td class="px-6 py-3.5 text-center">
                                    @if($item->status == 'Disetujui')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-bold rounded-md border border-emerald-100 uppercase">Disetujui</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-[9px] font-bold rounded-md border border-rose-100 uppercase">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-20 text-center opacity-40 text-[10px] font-bold uppercase tracking-widest text-slate-500 italic">Belum ada riwayat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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
            document.getElementById('realtime-clock').textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guru-layout>