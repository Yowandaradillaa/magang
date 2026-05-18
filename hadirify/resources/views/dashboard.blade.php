<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-4 duration-300">
        
        <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-[22px] font-extrabold text-[#1a2535]">Dashboard Siswa</h2>
                <p class="mt-1 text-[13px] text-[#5a6a80]">
                    Selamat datang kembali, <strong class="text-[#1a2535]">{{ Auth::user()->name }}</strong>! 👋
                </p>
            </div>
            <a href="#" class="flex items-center gap-2 rounded-[10px] bg-[#00b4d8] px-5 py-2.5 text-[13px] font-semibold text-white shadow-sm transition-colors hover:bg-[#009bc0]">
                <i data-lucide="camera" class="w-[18px] h-[18px]"></i> Scan QR Sekarang
            </a>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <div class="relative overflow-hidden rounded-[14px] bg-white p-5 shadow-sm border-t-4 border-[#00b4d8]">
                <div class="mb-3 text-[#0f4c75]"><i data-lucide="calendar-days" class="w-7 h-7"></i></div>
                <div class="font-mono text-[28px] font-extrabold text-[#1a2535]">87%</div>
                <div class="text-[12px] font-medium text-[#5a6a80]">Kehadiran Bulan Ini</div>
            </div>
            <div class="relative overflow-hidden rounded-[14px] bg-white p-5 shadow-sm border-t-4 border-[#06d6a0]">
                <div class="mb-3 text-[#0cb47a]"><i data-lucide="check-circle-2" class="w-7 h-7"></i></div>
                <div class="font-mono text-[28px] font-extrabold text-[#1a2535]">18</div>
                <div class="text-[12px] font-medium text-[#5a6a80]">Hari Hadir</div>
            </div>
            <div class="relative overflow-hidden rounded-[14px] bg-white p-5 shadow-sm border-t-4 border-[#ffd166]">
                <div class="mb-3 text-[#f4a60a]"><i data-lucide="file-text" class="w-7 h-7"></i></div>
                <div class="font-mono text-[28px] font-extrabold text-[#1a2535]">2</div>
                <div class="text-[12px] font-medium text-[#5a6a80]">Izin / Sakit</div>
            </div>
            <div class="relative overflow-hidden rounded-[14px] bg-white p-5 shadow-sm border-t-4 border-[#ef476f]">
                <div class="mb-3 text-[#ef476f]"><i data-lucide="x-circle" class="w-7 h-7"></i></div>
                <div class="font-mono text-[28px] font-extrabold text-[#1a2535]">1</div>
                <div class="text-[12px] font-medium text-[#5a6a80]">Alpa</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <div class="lg:col-span-2 overflow-hidden rounded-[14px] bg-white shadow-sm border border-[#e2e8f0]">
                <div class="border-b border-[#e2e8f0] p-[18px_22px]">
                    <h3 class="text-[15px] font-bold text-[#1a2535]">📅 Kalender Kehadiran</h3>
                </div>
                <div class="p-5">
                    <div class="mb-2 grid grid-cols-7 text-center text-[10px] font-bold text-[#90a0b4]">
                        <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div>Sab</div><div>Min</div>
                    </div>
                    <div class="grid grid-cols-7 gap-1">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="h-10 rounded-lg bg-slate-50"></div>
                        @endfor
                        @for ($date = 1; $date <= 28; $date++)
                            <div class="flex h-10 flex-col items-center justify-center rounded-lg text-[13px] font-bold bg-[#06d6a0]/10 text-[#0cb47a]">
                                {{ $date }}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-[14px] bg-white shadow-sm border border-[#e2e8f0]">
                <div class="border-b border-[#e2e8f0] p-[18px_22px]">
                    <h3 class="text-[15px] font-bold text-[#1a2535]">📢 Pengumuman</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="rounded-xl border border-[#e2e8f0] p-4 bg-sky-50/50">
                        <h4 class="text-[13.5px] font-bold text-[#1a2535]">Jadwal UTS Semester 2</h4>
                        <p class="text-[12.5px] text-[#5a6a80] mt-1">UTS dilaksanakan mulai 20–27 Mei 2026.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>