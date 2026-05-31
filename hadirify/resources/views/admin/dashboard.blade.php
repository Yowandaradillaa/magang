<x-admin-layout>
    <div class="fade-in space-y-6">
        
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-black text-[#0f4c75] tracking-tight">Dashboard Utama Admin</h2>
                <p class="text-sm text-[#5a6a80]">Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>! Sistem kendali absensi sekolah.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#0f4c75]"></div>
                <div class="mb-3"><i data-lucide="users" class="w-8 h-8 text-[#0f4c75]"></i></div>
                
                <div class="text-2xl font-black text-[#1a2535]">{{ $stats['total_siswa'] }}</div>
                
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Total Siswa Terdaftar</div>
            </div>

            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#00b4d8]"></div>
                <div class="mb-3"><i data-lucide="graduation-cap" class="w-8 h-8 text-[#00b4d8]"></i></div>
                
                <div class="text-2xl font-black text-[#1a2535]">{{ $stats['total_guru'] }}</div>
                
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Total Guru Terdaftar</div>
            </div>

            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#06d6a0]"></div>
                <div class="mb-3"><i data-lucide="check-circle" class="w-8 h-8 text-[#06d6a0]"></i></div>
                
                <div class="text-2xl font-black text-[#1a2535]">{{ $stats['hadir_hari_ini'] }}</div>
                
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Siswa Hadir Hari Ini</div>
            </div>

            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#ef476f]"></div>
                <div class="mb-3"><i data-lucide="alert-triangle" class="w-8 h-8 text-[#ef476f]"></i></div>
                
                <div class="text-2xl font-black text-[#ef476f]">{{ $stats['alpa_hari_ini'] }}</div>
                
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Siswa Alpa Hari Ini</div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm">
            <h3 class="text-base font-extrabold text-[#1a2535] mb-4">Akses Cepat Layanan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="/admin/akun" class="p-4 bg-slate-50 hover:bg-[#0f4c75]/5 border border-[#e2e8f0] rounded-xl flex flex-col items-center justify-center text-center transition-all">
                    <i data-lucide="user-cog" class="w-7 h-7 mb-2 text-[#0f4c75]"></i>
                    <span class="text-[13px] font-bold text-[#0f4c75]">Kelola Akun</span>
                </a>
                <a href="/admin/kelas" class="p-4 bg-slate-50 hover:bg-[#00b4d8]/5 border border-[#e2e8f0] rounded-xl flex flex-col items-center justify-center text-center transition-all">
                    <i data-lucide="building" class="w-7 h-7 mb-2 text-[#00b4d8]"></i>
                    <span class="text-[13px] font-bold text-[#00b4d8]">Atur Kelas & Jadwal</span>
                </a>
                <a href="/admin/koreksi" class="p-4 bg-slate-50 hover:bg-[#ffd166]/10 border border-[#e2e8f0] rounded-xl flex flex-col items-center justify-center text-center transition-all">
                    <i data-lucide="edit-3" class="w-7 h-7 mb-2 text-[#b07500]"></i>
                    <span class="text-[13px] font-bold text-[#b07500]">Koreksi Absen</span>
                </a>
                <a href="/admin/laporan" class="p-4 bg-slate-50 hover:bg-slate-100 border border-[#e2e8f0] rounded-xl flex flex-col items-center justify-center text-center transition-all">
                    <i data-lucide="bar-chart-2" class="w-7 h-7 mb-2 text-[#5a6a80]"></i>
                    <span class="text-[13px] font-bold text-[#5a6a80]">Laporan Sekolah</span>
                </a>
            </div>
        </div>

    </div>
</x-admin-layout>