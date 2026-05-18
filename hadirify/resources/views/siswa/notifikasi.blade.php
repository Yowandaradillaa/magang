<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out max-w-3xl mx-auto space-y-6">
        
        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex justify-between items-center">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#ef476f] uppercase bg-[#ef476f]/10 px-3 py-1 rounded-full">Pemberitahuan</span>
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Pusat Notifikasi</h1>
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Informasi konfirmasi kehadiran atau pengumuman penting guru kelas Anda.</p>
            </div>
            <button class="text-[12px] font-bold text-[#00b4d8] hover:underline whitespace-nowrap outline-none">Tandai Semua Dibaca</button>
        </div>

        <div class="space-y-3.5">
            
            <div class="group flex gap-4 bg-white p-5 rounded-[20px] border-l-4 border-l-[#00b4d8] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="p-3 bg-[#00b4d8]/10 text-[#00b4d8] rounded-xl h-fit">
                    <i data-lucide="check-square" class="w-5 h-5" stroke-width="2.5"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start gap-4">
                        <h3 class="text-[14px] font-extrabold text-[#1a2535] group-hover:text-[#00b4d8] transition-colors">Absensi Berhasil Dikonfirmasi</h3>
                        <span class="flex h-2 w-2 relative mt-1 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ef476f] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#ef476f]"></span>
                        </span>
                    </div>
                    <p class="text-[12.5px] text-[#5a6a80] mt-1 leading-relaxed">Selamat, data pemindaian QR Code Anda pada jam pelajaran Fisika (Bu Sari Dewi) terverifikasi Aman.</p>
                    <span class="text-[10.5px] font-bold text-[#90a0b4] mt-3 inline-block font-mono">10 menit yang lalu</span>
                </div>
            </div>

            <div class="group flex gap-4 bg-white p-5 rounded-[20px] border-l-4 border-l-[#f4a60a] border border-[#e2e8f0] shadow-sm hover:shadow-md transition-all cursor-pointer">
                <div class="p-3 bg-amber-500/10 text-[#f4a60a] rounded-xl h-fit">
                    <i data-lucide="file-clock" class="w-5 h-5" stroke-width="2.5"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start gap-4">
                        <h3 class="text-[14px] font-extrabold text-[#1a2535] group-hover:text-[#f4a60a] transition-colors">Izin Sakit Disetujui Wali Kelas</h3>
                        <span class="flex h-2 w-2 relative mt-1 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ef476f] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#ef476f]"></span>
                        </span>
                    </div>
                    <p class="text-[12.5px] text-[#5a6a80] mt-1 leading-relaxed">Permohonan izin sakit Anda untuk tanggal 15 Mei telah ditinjau dan status diubah menjadi Sakit (S) sah.</p>
                    <span class="text-[10.5px] font-bold text-[#90a0b4] mt-3 inline-block font-mono">2 jam yang lalu</span>
                </div>
            </div>

            <div class="group flex gap-4 bg-white p-5 rounded-[20px] border border-[#e2e8f0] opacity-80 shadow-sm hover:opacity-100 transition-all cursor-pointer">
                <div class="p-3 bg-slate-100 text-slate-400 rounded-xl h-fit">
                    <i data-lucide="megaphone" class="w-5 h-5" stroke-width="2.5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-[14px] font-extrabold text-[#1a2535] group-hover:text-[#0f4c75] transition-colors">Pengumuman Baru Diunggah</h3>
                    <p class="text-[12.5px] text-[#5a6a80] mt-1 leading-relaxed">Pihak Kurikulum mengunggah berkas jadwal pendaftaran UTS Semester Genap. Silakan periksa berkas di papan utama.</p>
                    <span class="text-[10.5px] font-bold text-[#90a0b4] mt-3 inline-block font-mono">Kemarin, 14:20 WIB</span>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>