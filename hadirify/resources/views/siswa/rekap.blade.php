<x-app-layout>
    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-6">
        
        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm">
            <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Riwayat</span>
            <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Rekap Kehadiran</h1>
            <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Pantau seluruh catatan kehadiran dan log absensi digital Anda secara transparan.</p>
        </div>

        <div class="bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden">
            <div class="border-b border-[#e2e8f0] p-5 bg-[#f7f9fc] flex justify-between items-center">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                        <i data-lucide="clock" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Log Absensi Siswa</h3>
                </div>
                <span class="text-xs font-bold text-[#90a0b4]">Menampilkan 5 Data Terakhir</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13.5px] border-collapse">
                    <thead class="bg-[#f7f9fc] text-[11px] font-black uppercase text-[#90a0b4] tracking-wider border-b border-[#e2e8f0]">
                        <tr>
                            <th class="p-4 pl-6">Tanggal</th>
                            <th class="p-4">Mata Pelajaran</th>
                            <th class="p-4">Jam Absen</th>
                            <th class="p-4">Metode</th>
                            <th class="p-4 text-center pr-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0] font-medium text-[#5a6a80]">
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 font-mono text-xs text-[#1a2535] font-bold">19 Mei 2026</td>
                            <td class="p-4 font-bold text-[#1a2535]">Fisika</td>
                            <td class="p-4 font-mono text-xs">10:17:42 WIB</td>
                            <td class="p-4"><span class="px-2 py-1 bg-sky-50 text-[#00b4d8] rounded-md text-xs font-bold border border-sky-100">QR Code</span></td>
                            <td class="p-4 text-center pr-6">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-[#0cb47a] px-3 py-1 rounded-full text-xs font-black border border-emerald-100">Hadir</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 font-mono text-xs text-[#1a2535] font-bold">19 Mei 2026</td>
                            <td class="p-4 font-bold text-[#1a2535]">Matematika</td>
                            <td class="p-4 font-mono text-xs">07:04:11 WIB</td>
                            <td class="p-4"><span class="px-2 py-1 bg-sky-50 text-[#00b4d8] rounded-md text-xs font-bold border border-sky-100">QR Code</span></td>
                            <td class="p-4 text-center pr-6">
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-[#0cb47a] px-3 py-1 rounded-full text-xs font-black border border-emerald-100">Hadir</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 font-mono text-xs text-[#1a2535] font-bold">18 Mei 2026</td>
                            <td class="p-4 font-bold text-[#1a2535]">Bahasa Inggris</td>
                            <td class="p-4 font-mono text-xs text-slate-400">—</td>
                            <td class="p-4"><span class="px-2 py-1 bg-amber-50 text-[#f4a60a] rounded-md text-xs font-bold border border-amber-100">Sistem</span></td>
                            <td class="p-4 text-center pr-6">
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-[#f4a60a] px-3 py-1 rounded-full text-xs font-black border border-amber-100">Izin</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="p-4 pl-6 font-mono text-xs text-[#1a2535] font-bold">15 Mei 2026</td>
                            <td class="p-4 font-bold text-[#1a2535]">Kimia</td>
                            <td class="p-4 font-mono text-xs text-slate-400">—</td>
                            <td class="p-4"><span class="px-2 py-1 bg-amber-50 text-amber-600 rounded-md text-xs font-bold border border-amber-100">Sistem</span></td>
                            <td class="p-4 text-center pr-6">
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-xs font-black border border-amber-100">Sakit</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>