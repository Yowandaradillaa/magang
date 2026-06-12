<x-guru-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-6">
        
        <!-- Header Panel -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-sky-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-[#0b1e36] uppercase bg-amber-500/10 border border-amber-400/20 px-3.5 py-1.5 rounded-full">Metode Manual</span>
                <h1 class="text-2xl font-black text-[#0b1e36] tracking-tight mt-3">Presensi Manual Kelas</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1">Catat kehadiran siswa secara langsung saat pembelajaran tatap muka di kelas.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('guru.absensi-manual') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Config Session Card -->
            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm">
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Pilih Jadwal Mengajar</label>
                    <select name="jadwal_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-slate-100 outline-none text-[13.5px] font-bold text-[#0b1e36] bg-white">
    <option value="">-- Pilih Jadwal --</option>
    @foreach($jadwals as $j)
        <option value="{{ $j->id }}">{{ $j->kelas->name }} — {{ $j->mapel->nama_mapel }} ({{ $j->jam_mulai }})</option>
    @endforeach
</select>
                </div>
            </div>

            <!-- Student List Card -->
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 p-5 bg-slate-50/50 flex justify-between items-center flex-wrap gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-sky-50 text-sky-600 rounded-xl border border-sky-100">
                            <i data-lucide="users" class="w-4 h-4" stroke-width="2.5"></i>
                        </div>
                        <h3 class="text-[15px] font-extrabold text-[#0b1e36]">Daftar Absensi Siswa</h3>
                    </div>
                    
                    <button type="button" class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-[11px] font-black text-[#0b1e36] shadow-sm hover:bg-slate-50 transition-colors cursor-pointer" onclick="setAllStatus('H')">
                        <i data-lucide="check" class="w-4 h-4 text-emerald-500" stroke-width="3"></i> Set Hadir Semua
                    </button>
                </div>

                <div class="divide-y divide-slate-100" id="manual-list">
    @forelse($siswa as $index => $s)
        <div class="flex items-center justify-between p-4 px-6 hover:bg-slate-50/50 transition-colors flex-wrap sm:flex-nowrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 bg-slate-100 text-slate-500 font-mono text-[11px] font-extrabold flex items-center justify-center rounded-lg border border-slate-200/60">
                    {{ $index + 1 }}
                </div>
                <div class="font-bold text-[#0b1e36] text-[14px]">{{ $s->name }}</div>
            </div>
            
            <div class="flex items-center gap-2">
                <input type="hidden" name="absensi_data[{{ $s->id }}]" id="input_status_{{ $s->id }}" class="status-input" value="H">
                <button type="button" id="btn_{{ $s->id }}_H" ...>H</button>
                <button type="button" id="btn_{{ $s->id }}_A" ...>A</button>
                <button type="button" id="btn_{{ $s->id }}_S" ...>S</button>
                <button type="button" id="btn_{{ $s->id }}_I" ...>I</button>
            </div>
        </div>
    @empty
        <p class="p-6 text-center text-slate-400">Pilih jadwal untuk menampilkan daftar siswa.</p>
    @endforelse
</div>
            </div>

            <!-- Submit Button Area -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-[#0b1e36] to-[#112d52] hover:from-[#112d52] hover:to-[#1a3d6d] text-white px-8 py-4 rounded-xl text-[14px] font-bold shadow-lg shadow-blue-950/20 cursor-pointer">
                    <i data-lucide="save" class="w-4.5 h-4.5"></i> Simpan Data Presensi
                </button>
            </div>
        </form>
    </div>

    <script>
        // Init H buttons to be active on load
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.status-input');
            inputs.forEach(input => {
                const id = input.id.split('_')[2];
                const btn = document.getElementById(`btn_${id}_H`);
                if(btn) {
                    btn.className = 'status-btn w-10 h-10 rounded-xl border-2 font-extrabold text-[12.5px] transition-all cursor-pointer flex items-center justify-center outline-none border-emerald-500 bg-emerald-500 text-white';
                }
            });
        });

        function changeStatus(id, status, activeClasses) {
            // Update hidden input
            document.getElementById('input_status_' + id).value = status;
            
            // Reset buttons classes in the row
            const codes = ['H', 'A', 'S', 'I'];
            codes.forEach(c => {
                const b = document.getElementById(`btn_${id}_${c}`);
                if (b) {
                    b.className = 'status-btn w-10 h-10 rounded-xl border-2 border-slate-200 bg-white font-extrabold text-[12.5px] text-slate-400 hover:border-slate-300 transition-all cursor-pointer flex items-center justify-center outline-none';
                }
            });

            // Set active class
            const activeBtn = document.getElementById(`btn_${id}_${status}`);
            if (activeBtn) {
                activeBtn.className = `status-btn w-10 h-10 rounded-xl border-2 font-extrabold text-[12.5px] transition-all cursor-pointer flex items-center justify-center outline-none ${activeClasses}`;
            }
        }

        function setAllStatus(status) {
            document.querySelectorAll('.status-input').forEach(input => {
                const id = input.id.split('_')[2];
                let classes = 'border-emerald-500 bg-emerald-500 text-white';
                if (status === 'A') classes = 'border-rose-500 bg-rose-500 text-white';
                else if (status === 'S') classes = 'border-sky-500 bg-sky-500 text-white';
                else if (status === 'I') classes = 'border-amber-500 bg-amber-500 text-white';
                
                changeStatus(id, status, classes);
            });
        }
    </script>
</x-guru-layout>