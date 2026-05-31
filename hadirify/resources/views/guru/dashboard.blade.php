<x-guru-layout>
    <div x-data="{ qrMembuka: false, kelasAktif: '' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
        <div class="bg-white p-8 rounded-[24px] border border-[#e2e8f0] shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Ruang Pendidik</span>
                
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2.5">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
                
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Sistem Hadirify siap membantu Anda mengelola data presensi kelas hari ini secara otomatis.</p>
            </div>
            <div class="bg-[#f7f9fc] border border-[#e2e8f0] px-4 py-2.5 rounded-xl font-mono text-xs text-[#0f4c75] font-bold flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-4 h-4 text-[#00b4d8]"></i> {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-6 rounded-[20px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#06d6a0]"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider">Hadir</span>
                    <i data-lucide="check-circle" class="w-5 h-5 text-[#06d6a0]"></i>
                </div>
                
                <div class="text-3xl font-black text-[#1a2535] font-mono">{{ $stats['hadir'] }} <span class="text-xs font-bold text-[#90a0b4]">Siswa</span></div>
            </div>
            
            <div class="bg-white p-6 rounded-[20px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#00b4d8]"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider">Izin</span>
                    <i data-lucide="user-check" class="w-5 h-5 text-[#00b4d8]"></i>
                </div>
                
                <div class="text-3xl font-black text-[#1a2535] font-mono">{{ $stats['izin'] }} <span class="text-xs font-bold text-[#90a0b4]">Siswa</span></div>
            </div>
            
            <div class="bg-white p-6 rounded-[20px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#ffd166]"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider">Sakit</span>
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-[#ffd166]"></i>
                </div>
                
                <div class="text-3xl font-black text-[#1a2535] font-mono">{{ $stats['sakit'] }} <span class="text-xs font-bold text-[#90a0b4]">Siswa</span></div>
            </div>
            
            <div class="bg-white p-6 rounded-[20px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#ef476f]"></div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider">Alpa</span>
                    <i data-lucide="alert-circle" class="w-5 h-5 text-[#ef476f]"></i>
                </div>
                
                <div class="text-3xl font-black text-[#ef476f] font-mono">{{ $stats['alpa'] }} <span class="text-xs font-bold text-[#90a0b4]">Siswa</span></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm space-y-4">
            <h3 class="text-base font-extrabold text-[#1a2535]">Kendali Sesi Presensi</h3>
            <div class="flex flex-wrap gap-4">
                <a href="/guru/manual" class="flex-1 min-w-[200px] p-5 bg-slate-50 hover:bg-[#0f4c75]/5 border border-[#e2e8f0] rounded-xl flex items-center gap-4 transition-all">
                    <div class="p-3 bg-[#0f4c75]/10 text-[#0f4c75] rounded-xl">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1a2535]">Presensi Manual</h4>
                        <p class="text-xs font-medium text-[#90a0b4] mt-0.5">Input absensi tatap muka langsung</p>
                    </div>
                </a>
                <a href="/guru/qr" class="flex-1 min-w-[200px] p-5 bg-slate-50 hover:bg-[#00b4d8]/5 border border-[#e2e8f0] rounded-xl flex items-center gap-4 transition-all">
                    <div class="p-3 bg-[#00b4d8]/10 text-[#00b4d8] rounded-xl">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-[#1a2535]">Rilis QR Code</h4>
                        <p class="text-xs font-medium text-[#90a0b4] mt-0.5">Buka sesi scan mandiri siswa</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</x-guru-layout>