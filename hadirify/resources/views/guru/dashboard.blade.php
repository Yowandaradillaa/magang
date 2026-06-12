<x-guru-layout>
    <div x-data="{ qrMembuka: false, kelasAktif: '' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
        <!-- Welcome Board -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-amber-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-[#0b1e36] uppercase bg-amber-500/10 border border-amber-500/10 px-3.5 py-1.5 rounded-full">Ruang Pendidik</span>
                <h1 class="text-2xl md:text-3xl font-black text-[#0b1e36] tracking-tight mt-3">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1">Sistem Hadirify siap membantu Anda mengelola data presensi kelas hari ini secara otomatis.</p>
            </div>
            
            <div class="bg-slate-50 border border-slate-200/60 px-5 py-3 rounded-xl font-mono text-xs text-[#0b1e36] font-bold flex items-center gap-2 shrink-0">
                <i data-lucide="calendar-days" class="w-4 h-4 text-amber-500"></i> {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <!-- Class Attendance Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Hadir -->
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-emerald-500"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Hadir</span>
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
                </div>
                <div class="text-3xl font-black text-[#0b1e36]">{{ $stats['hadir'] }} <span class="text-xs font-bold text-slate-400">Siswa</span></div>
            </div>
            
            <!-- Izin -->
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-sky-500"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Izin</span>
                    <i data-lucide="user-check" class="w-5 h-5 text-sky-500"></i>
                </div>
                <div class="text-3xl font-black text-[#0b1e36]">{{ $stats['izin'] }} <span class="text-xs font-bold text-slate-400">Siswa</span></div>
            </div>
            
            <!-- Sakit -->
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-amber-500"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Sakit</span>
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-amber-500"></i>
                </div>
                <div class="text-3xl font-black text-[#0b1e36]">{{ $stats['sakit'] }} <span class="text-xs font-bold text-slate-400">Siswa</span></div>
            </div>
            
            <!-- Alpa -->
            <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-[4px] bg-rose-500"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Alpa</span>
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
                </div>
                <div class="text-3xl font-black text-rose-600">{{ $stats['alpa'] }} <span class="text-xs font-bold text-rose-400">Siswa</span></div>
            </div>
        </div>

        <!-- Quick Access Services -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm space-y-5">
            <h3 class="text-[15px] font-extrabold text-[#0b1e36] tracking-tight">Kendali Sesi Presensi Kelas</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <a href="/guru/manual" class="group p-5 bg-slate-50/50 hover:bg-sky-50/20 border border-slate-100 hover:border-sky-500 rounded-2xl flex items-center gap-5 transition-all duration-300">
                    <div class="p-3 bg-sky-50 text-sky-600 rounded-xl border border-sky-100 group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-bold text-[#0b1e36]">Presensi Manual</h4>
                        <p class="text-xs font-semibold text-slate-400 mt-1">Input data kehadiran tatap muka langsung di kelas.</p>
                    </div>
                </a>
                <a href="/guru/qr" class="group p-5 bg-slate-50/50 hover:bg-amber-50/20 border border-slate-100 hover:border-amber-500 rounded-2xl flex items-center gap-5 transition-all duration-300">
                    <div class="p-3 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 group-hover:scale-105 transition-transform duration-300">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-bold text-[#0b1e36]">Rilis QR Code</h4>
                        <p class="text-xs font-semibold text-slate-400 mt-1">Buka gerbang pemindaian mandiri bagi siswa kelas.</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</x-guru-layout>