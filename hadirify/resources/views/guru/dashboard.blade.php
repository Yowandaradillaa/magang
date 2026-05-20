<x-guru-layout>
    <div x-data="{ qrMembuka: false, kelasAktif: '' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-8">
        
        <div class="bg-white p-8 rounded-[24px] border border-[#e2e8f0] shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Ruang Pendidik</span>
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2.5">Selamat Datang Kembali, Pak Budi!</h1>
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Sistem Hadirify siap membantu Anda mengelola data presensi kelas hari ini secara otomatis.</p>
            </div>
            <div class="bg-[#f7f9fc] border border-[#e2e8f0] px-4 py-2.5 rounded-xl font-mono text-xs text-[#0f4c75] font-bold flex items-center gap-2">
                <i data-lucide="calendar-days" class="w-4 h-4 text-[#00b4d8]"></i> 19 Mei 2026 • 09:45 WIB
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-4">
                <div class="p-4 bg-[#0f4c75]/10 text-[#0f4c75] rounded-2xl">
                    <i data-lucide="book-open" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-[#90a0b4] uppercase tracking-wider block">Total Jadwal</span>
                    <h3 class="text-xl font-black text-[#1a2535] mt-0.5">3 Kelas</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-4">
                <div class="p-4 bg-emerald-50 text-[#0cb47a] rounded-2xl">
                    <i data-lucide="user-check" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-[#90a0b4] uppercase tracking-wider block">Rata-rata Hadir</span>
                    <h3 class="text-xl font-black text-[#1a2535] mt-0.5">94.2%</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-4">
                <div class="p-4 bg-amber-50 text-[#f4a60a] rounded-2xl">
                    <i data-lucide="file-text" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-[#90a0b4] uppercase tracking-wider block">Pending Izin</span>
                    <h3 class="text-xl font-black text-[#1a2535] mt-0.5">2 Siswa</h3>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex items-center gap-4">
                <div class="p-4 bg-rose-50 text-[#ef476f] rounded-2xl">
                    <i data-lucide="user-x" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-[#90a0b4] uppercase tracking-wider block">Mangkir/Alfa</span>
                    <h3 class="text-xl font-black text-[#1a2535] mt-0.5">1 Siswa</h3>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden lg:col-span-2">
                <div class="border-b border-[#e2e8f0] p-5 bg-[#f7f9fc] flex items-center gap-2.5">
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                        <i data-lucide="clipboard-list" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Jadwal Mengajar Hari Ini</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13.5px] border-collapse">
                        <thead class="bg-[#f7f9fc] text-[11px] font-black uppercase text-[#90a0b4] tracking-wider border-b border-[#e2e8f0]">
                            <tr>
                                <th class="p-4 pl-6">Jam Ke</th>
                                <th class="p-4">Kelas & Mapel</th>
                                <th class="p-4">Ruang</th>
                                <th class="p-4 text-center pr-6">Aksi Absensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0] font-medium text-[#5a6a80]">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4 pl-6 font-mono text-xs font-bold text-[#1a2535]">07:00 - 08:30</td>
                                <td class="p-4">
                                    <div class="font-bold text-[#1a2535]">Fisika</div>
                                    <div class="text-[11px] text-[#90a0b4] font-semibold mt-0.5">Kelas X-A (32 Siswa)</div>
                                </td>
                                <td class="p-4 font-bold text-[#0f4c75]">Lab Fisika</td>
                                <td class="p-4 text-center pr-6">
                                    <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-bold border border-slate-200">Selesai</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition-colors bg-sky-50/20">
                                <td class="p-4 pl-6 font-mono text-xs font-bold text-[#00b4d8]">09:30 - 11:00</td>
                                <td class="p-4">
                                    <div class="font-bold text-[#1a2535]">Fisika Dasar</div>
                                    <div class="text-[11px] text-[#00b4d8] font-bold mt-0.5">Kelas XI-B (30 Siswa) — Sedang Berjalan</div>
                                </td>
                                <td class="p-4 font-bold text-[#0f4c75]">Ruang 304</td>
                                <td class="p-4 text-center pr-6">
                                    <button @click="qrMembuka = true; kelasAktif = 'XI-B'" 
                                        class="px-3 py-1.5 bg-[#00b4d8] hover:bg-[#0096b4] text-white rounded-lg text-xs font-black shadow-sm flex items-center gap-1 mx-auto transition-colors outline-none">
                                        <i data-lucide="qr-code" class="w-3.5 h-3.5"></i> Kelola QR
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="p-4 pl-6 font-mono text-xs font-bold text-slate-400">13:00 - 14:30</td>
                                <td class="p-4 text-slate-400">
                                    <div class="font-bold">Astronomi (Peminatan)</div>
                                    <div class="text-[11px] font-semibold mt-0.5">Kelas XII-A (28 Siswa)</div>
                                </td>
                                <td class="p-4 font-bold text-slate-400">Ruang Multimedia</td>
                                <td class="p-4 text-center pr-6 text-slate-400 text-xs font-bold">— Belum Mulai</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex flex-col justify-between min-h-[300px]">
                <div>
                    <div class="flex items-center gap-2 text-[#0f4c75] mb-4">
                        <i data-lucide="cpu" class="w-5 h-5 text-[#00b4d8]" stroke-width="2.5"></i>
                        <h4 class="text-[14px] font-black text-[#1a2535]">Kontrol Token QR</h4>
                    </div>
                    
                    <div x-show="!qrMembuka" class="text-center py-8 space-y-3">
                        <div class="w-16 h-16 bg-slate-50 border border-dashed border-[#e2e8f0] text-slate-300 rounded-full flex items-center justify-center mx-auto">
                            <i data-lucide="qr-code" class="w-8 h-8"></i>
                        </div>
                        <p class="text-xs text-[#5a6a80] font-medium max-w-[200px] mx-auto">Klik tombol <b>"Kelola QR"</b> pada tabel kelas untuk memunculkan token siswa.</p>
                    </div>

                    <div x-show="qrMembuka" x-cloak class="space-y-4 text-center border border-sky-100 bg-sky-50/30 p-4 rounded-xl animate-in fade-in duration-200">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-wider text-[#00b4d8]">Kelas Terpilih</span>
                            <h5 class="text-base font-black text-[#1a2535]" x-text="'Fisika — Kelas ' + kelasAktif"></h5>
                        </div>
                        
                        <div class="w-36 h-36 bg-white p-2 border border-[#e2e8f0] rounded-xl mx-auto shadow-sm flex items-center justify-center relative group">
                            <i data-lucide="qr-code" class="w-28 h-28 text-[#0f4c75]"></i>
                            <div class="absolute inset-0 bg-emerald-500/10 flex items-center justify-center rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="bg-emerald-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">Token Aktif</span>
                            </div>
                        </div>
                        
                        <p class="text-[11px] font-bold text-emerald-600 flex items-center justify-center gap-1 animate-pulse">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Token di-refresh otomatis tiap 30 detik
                        </p>
                    </div>
                </div>

                <div x-show="qrMembuka" x-cloak class="pt-4 border-t border-[#e2e8f0] flex gap-2">
                    <button @click="qrMembuka = false" class="flex-1 py-2 rounded-xl border border-rose-200 text-rose-500 hover:bg-rose-50 font-bold text-xs transition-colors outline-none">
                        Tutup Kelas
                    </button>
                    <button class="flex-1 py-2 rounded-xl bg-[#0f4c75] hover:bg-[#1b6ca8] text-white font-bold text-xs shadow-sm transition-colors outline-none flex items-center justify-center gap-1">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i> Acak Ulang
                    </button>
                </div>
            </div>

        </div>

    </div>
</x-guru-layout>