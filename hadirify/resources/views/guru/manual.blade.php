<x-guru-layout>
    <!-- Container Utama: Fixed Height agar Header diam & Tabel bisa di-scroll -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-500 flex items-center justify-center rounded-lg shadow-lg shadow-amber-100">
                        <i data-lucide="edit-3" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Presensi Manual</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">Input kehadiran tatap muka langsung</p>
                    </div>
                </div>

                <!-- Clock & Date -->
                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-lg border border-slate-100">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ now()->translatedFormat('d M Y') }}</p>
                        <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">00:00:00</p>
                    </div>
                    <div class="w-[1px] h-4 bg-slate-200"></div>
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('guru.absensi-manual') }}" method="POST" class="flex flex-col flex-1 min-h-0 space-y-4">
            @csrf

            <!-- ================= SECTION 2: CONFIG & FILTER (FIXED) ================= -->
            <div class="flex-none grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-8 bg-white p-4 rounded-xl border border-slate-200/60 shadow-sm flex items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-1">Pilih Jadwal Mengajar</label>
                        <div class="relative">
                            <select name="jadwal_id" required class="w-full pl-3 pr-10 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-[#0b1e36] outline-none focus:border-amber-500 appearance-none transition-all">
                                <option value="">-- Klik untuk memilih kelas --</option>
                                @foreach($jadwals as $j)
                                    <option value="{{ $j->id }}">{{ $j->kelas->nama_kelas }} — {{ $j->mapel->nama_mapel }} ({{ $j->jam_mulai }})</option>
                                @endforeach
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-4 bg-white p-4 rounded-xl border border-slate-200/60 shadow-sm flex items-center justify-center">
                    <button type="button" onclick="setAllStatus('H')" class="w-full h-full flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all border border-emerald-100 border-dashed">
                        <i data-lucide="check-check" class="w-4 h-4"></i> Hadirkan Semua Siswa
                    </button>
                </div>
            </div>

            <!-- ================= SECTION 3: STUDENT LIST (SCROLLABLE) ================= -->
            <div class="flex-1 min-h-0 bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                <div class="flex-1 overflow-y-auto no-scrollbar relative">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white z-10 border-b border-slate-100 shadow-sm">
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Informasi Siswa</th>
                                <th class="px-6 py-4 text-right">Status Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($siswa as $index => $s)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-center font-mono text-[10px] text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-slate-800 text-xs">{{ $s->name }}</span>
                                    <p class="text-[9px] text-slate-400 font-mono tracking-tighter">{{ $s->nisn ?? 'NISN N/A' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        <input type="hidden" name="absensi_data[{{ $s->id }}]" id="input_status_{{ $s->id }}" class="status-input" value="H">
                                        
                                        <!-- Tombol Pilihan Status -->
                                        <button type="button" id="btn_{{ $s->id }}_H" onclick="changeStatus('{{ $s->id }}', 'H', 'bg-emerald-500 text-white border-emerald-600')" class="status-btn w-8 h-8 rounded-lg border border-slate-200 text-[10px] font-black transition-all">H</button>
                                        <button type="button" id="btn_{{ $s->id }}_S" onclick="changeStatus('{{ $s->id }}', 'S', 'bg-sky-500 text-white border-sky-600')" class="status-btn w-8 h-8 rounded-lg border border-slate-200 text-[10px] font-black transition-all">S</button>
                                        <button type="button" id="btn_{{ $s->id }}_I" onclick="changeStatus('{{ $s->id }}', 'I', 'bg-amber-500 text-white border-amber-600')" class="status-btn w-8 h-8 rounded-lg border border-slate-200 text-[10px] font-black transition-all">I</button>
                                        <button type="button" id="btn_{{ $s->id }}_A" onclick="changeStatus('{{ $s->id }}', 'A', 'bg-rose-500 text-white border-rose-600')" class="status-btn w-8 h-8 rounded-lg border border-slate-200 text-[10px] font-black transition-all">A</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-20 text-center opacity-30">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="users" class="w-10 h-10 mb-2"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest italic">Pilih jadwal untuk memuat data siswa</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ================= SECTION 4: SUBMIT (FIXED) ================= -->
            <div class="flex-none flex justify-end pb-4">
                <button type="submit" class="w-full md:w-auto h-12 px-10 bg-[#0b1e36] hover:bg-black text-white text-[11px] font-black uppercase tracking-[0.15em] rounded-xl shadow-lg shadow-blue-900/20 transition-all flex items-center justify-center gap-3 active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Presensi Kelas
                </button>
            </div>
        </form>
    </div>

    <script>
        // Realtime Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('realtime-clock').textContent = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ':' + now.getSeconds().toString().padStart(2, '0');
        }
        setInterval(updateClock, 1000); updateClock();

        // Init H buttons to be active on load
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.status-input').forEach(input => {
                const id = input.id.split('_')[2];
                changeStatus(id, 'H', 'bg-emerald-500 text-white border-emerald-600');
            });
        });

        function changeStatus(id, status, activeClasses) {
            document.getElementById('input_status_' + id).value = status;
            
            // Reset all buttons in this row
            const codes = ['H', 'S', 'I', 'A'];
            codes.forEach(c => {
                const b = document.getElementById(`btn_${id}_${c}`);
                if (b) b.className = 'status-btn w-8 h-8 rounded-lg border border-slate-200 bg-white text-slate-400 text-[10px] font-black transition-all hover:bg-slate-50';
            });

            // Activate chosen button
            const activeBtn = document.getElementById(`btn_${id}_${status}`);
            if (activeBtn) activeBtn.className = `status-btn w-8 h-8 rounded-lg border font-black text-[10px] transition-all shadow-sm ${activeClasses}`;
        }

        function setAllStatus(status) {
            document.querySelectorAll('.status-input').forEach(input => {
                const id = input.id.split('_')[2];
                let classes = 'bg-emerald-500 text-white border-emerald-600';
                if (status === 'A') classes = 'bg-rose-500 text-white border-rose-600';
                else if (status === 'S') classes = 'bg-sky-500 text-white border-sky-600';
                else if (status === 'I') classes = 'bg-amber-500 text-white border-amber-600';
                changeStatus(id, status, classes);
            });
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guru-layout>