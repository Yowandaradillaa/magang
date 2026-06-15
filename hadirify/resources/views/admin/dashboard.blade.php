<x-admin-layout>
    <div class="animate-in fade-in duration-700 space-y-6 px-2">
        
        <!-- Header Dashboard (Enterprise Look with Realtime Clock) -->
        <div class="bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-11 h-11 bg-[#0b1e36] flex items-center justify-center rounded-lg shadow-lg shadow-[#0b1e36]/10">
                        <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Console Utama Admin</h2>
                        <p class="text-xs text-slate-500 font-medium">
                            Status Sesi: <span class="text-emerald-600 font-bold inline-flex items-center gap-1"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif</span> 
                            • <span class="text-[#0b1e36] font-bold">{{ Auth::user()->name }}</span>
                        </p>
                    </div>
                </div>

                <!-- Bagian Tanggal & Jam Realtime -->
                <div class="flex items-center gap-3 px-4 py-2 bg-slate-50/50 rounded-xl border border-slate-100">
                    <div class="text-right">
                        <p id="realtime-date" class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                            {{ date('d F Y') }}
                        </p>
                        <p id="realtime-clock" class="text-sm font-bold text-[#0b1e36] font-mono leading-none">
                            {{ date('H:i:s') }}
                        </p>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-200 mx-1"></div>
                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- Kartu Statistik (Modern & Sleek) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-[#0b1e36]"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Siswa</p>
                        <div class="text-2xl font-bold text-[#0b1e36] tracking-tighter">{{ $stats['total_siswa'] }}</div>
                    </div>
                    <div class="p-2 bg-slate-50 text-slate-400 rounded-lg group-hover:bg-[#0b1e36] group-hover:text-white transition-colors">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-amber-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Guru</p>
                        <div class="text-2xl font-bold text-slate-900 tracking-tighter">{{ $stats['total_guru'] }}</div>
                    </div>
                    <div class="p-2 bg-slate-50 text-slate-400 rounded-lg group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-emerald-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Hadir Hari Ini</p>
                        <div class="text-2xl font-bold text-emerald-600 tracking-tighter">{{ $stats['hadir_hari_ini'] }}</div>
                    </div>
                    <div class="p-2 bg-emerald-50 text-emerald-400 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-rose-500"></div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alpa Hari Ini</p>
                        <div class="text-2xl font-bold text-rose-600 tracking-tighter">{{ $stats['alpa_hari_ini'] }}</div>
                    </div>
                    <div class="p-2 bg-rose-50 text-rose-400 rounded-lg group-hover:bg-rose-500 group-hover:text-white transition-colors">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modul Navigasi Utama -->
        <div class="space-y-4">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-3">
                <span class="w-8 h-[1px] bg-slate-200"></span>
                Modul Navigasi Utama
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="/admin/akun" class="group p-4 bg-white border border-slate-200/70 rounded-xl transition-all hover:shadow-lg hover:shadow-slate-200/50 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 text-[#0b1e36] rounded-lg flex items-center justify-center mb-3 group-hover:bg-[#0b1e36] group-hover:text-white transition-all">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 tracking-tight">Kelola Akun</span>
                </a>

                <a href="/admin/kelas" class="group p-4 bg-white border border-slate-200/70 rounded-xl transition-all hover:shadow-lg hover:shadow-slate-200/50 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 text-amber-500 rounded-lg flex items-center justify-center mb-3 group-hover:bg-amber-500 group-hover:text-white transition-all">
                        <i data-lucide="building" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 tracking-tight">Kelas & Jadwal</span>
                </a>

                <a href="/admin/koreksi" class="group p-4 bg-white border border-slate-200/70 rounded-xl transition-all hover:shadow-lg hover:shadow-slate-200/50 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 text-rose-500 rounded-lg flex items-center justify-center mb-3 group-hover:bg-rose-500 group-hover:text-white transition-all">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 tracking-tight">Koreksi Absen</span>
                </a>

                <a href="/admin/laporan" class="group p-4 bg-white border border-slate-200/70 rounded-xl transition-all hover:shadow-lg hover:shadow-slate-200/50 flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-slate-50 text-sky-500 rounded-lg flex items-center justify-center mb-3 group-hover:bg-sky-500 group-hover:text-white transition-all">
                        <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 tracking-tight">Laporan Sekolah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            
            // Format Tanggal (Contoh: 15 Juni 2026)
            const options = { day: '2-digit', month: 'long', year: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            
            // Format Jam (Contoh: 14:05:01)
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeStr = `${hours}:${minutes}:${seconds}`;

            document.getElementById('realtime-date').textContent = dateStr;
            document.getElementById('realtime-clock').textContent = timeStr;
        }

        // Jalankan setiap detik
        setInterval(updateClock, 1000);
        updateClock(); // Jalankan langsung saat load
    </script>
</x-admin-layout>