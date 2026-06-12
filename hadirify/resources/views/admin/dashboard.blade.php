<x-admin-layout>
    <div class="animate-in fade-in duration-300 space-y-8">
        
        <!-- Header Dashboard -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-rose-500/10 text-rose-600 rounded-lg">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Dashboard Utama Admin</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Selamat datang kembali, <span class="text-rose-500 font-bold">{{ Auth::user()->name }}</span>. Konsol kendali sistem presensi sekolah.</p>
            </div>
            <div class="text-[11px] font-mono font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 uppercase tracking-wider">
                Hak Akses: Super Administrator
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Siswa -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300 transform hover:-translate-y-[1px]">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#0b1e36]"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-[#0b1e36] font-mono">{{ $stats['total_siswa'] }}</div>
                    <div class="p-2 bg-[#0b1e36]/5 text-[#0b1e36] rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Total Siswa Terdaftar</div>
            </div>

            <!-- Total Guru -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300 transform hover:-translate-y-[1px]">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-amber-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-amber-500 font-mono">{{ $stats['total_guru'] }}</div>
                    <div class="p-2 bg-amber-500/10 text-amber-600 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Total Guru Terdaftar</div>
            </div>

            <!-- Hadir Hari Ini -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300 transform hover:-translate-y-[1px]">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-emerald-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-emerald-500 font-mono">{{ $stats['hadir_hari_ini'] }}</div>
                    <div class="p-2 bg-emerald-500/10 text-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Siswa Hadir Hari Ini</div>
            </div>

            <!-- Alpa Hari Ini -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm relative overflow-hidden group hover:shadow-md transition-all duration-300 transform hover:-translate-y-[1px]">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-rose-500"></div>
                <div class="flex items-center justify-between">
                    <div class="text-2xl font-black text-rose-500 font-mono">{{ $stats['alpa_hari_ini'] }}</div>
                    <div class="p-2 bg-rose-500/10 text-rose-600 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-3">Siswa Alpa Hari Ini</div>
            </div>
        </div>

        <!-- Akses Cepat Layanan -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
            <h3 class="text-sm font-bold text-[#0b1e36] mb-5 flex items-center gap-2">
                <i data-lucide="key" class="w-4.5 h-4.5 text-rose-500"></i>
                Akses Cepat Layanan Admin
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                <a href="/admin/akun" class="p-5 bg-slate-50 hover:bg-[#0b1e36]/5 hover:border-[#0b1e36]/20 border border-slate-200/60 rounded-xl flex flex-col items-center justify-center text-center transition-all group">
                    <div class="p-3 bg-white rounded-xl shadow-sm group-hover:shadow group-hover:-translate-y-0.5 transition-all mb-3 text-[#0b1e36]">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[13px] font-bold text-[#0b1e36]">Kelola Akun</span>
                </a>
                <a href="/admin/kelas" class="p-5 bg-slate-50 hover:bg-amber-500/5 hover:border-amber-500/20 border border-slate-200/60 rounded-xl flex flex-col items-center justify-center text-center transition-all group">
                    <div class="p-3 bg-white rounded-xl shadow-sm group-hover:shadow group-hover:-translate-y-0.5 transition-all mb-3 text-amber-500">
                        <i data-lucide="building" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[13px] font-bold text-amber-600">Kelas & Jadwal</span>
                </a>
                <a href="/admin/koreksi" class="p-5 bg-slate-50 hover:bg-rose-500/5 hover:border-rose-500/20 border border-slate-200/60 rounded-xl flex flex-col items-center justify-center text-center transition-all group">
                    <div class="p-3 bg-white rounded-xl shadow-sm group-hover:shadow group-hover:-translate-y-0.5 transition-all mb-3 text-rose-500">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[13px] font-bold text-rose-600">Koreksi Absen</span>
                </a>
                <a href="/admin/laporan" class="p-5 bg-slate-50 hover:bg-sky-500/5 hover:border-sky-500/20 border border-slate-200/60 rounded-xl flex flex-col items-center justify-center text-center transition-all group">
                    <div class="p-3 bg-white rounded-xl shadow-sm group-hover:shadow group-hover:-translate-y-0.5 transition-all mb-3 text-sky-500">
                        <i data-lucide="bar-chart-3" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[13px] font-bold text-sky-600">Laporan Sekolah</span>
                </a>
            </div>
        </div>

    </div>
</x-admin-layout>