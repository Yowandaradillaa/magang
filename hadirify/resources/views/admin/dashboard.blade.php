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
                <div class="text-2xl font-black text-[#1a2535]">312</div>
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Total Siswa Terdaftar</div>
            </div>
            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#06d6a0]"></div>
                <div class="mb-3"><i data-lucide="graduation-cap" class="w-8 h-8 text-[#06d6a0]"></i></div>
                <div class="text-2xl font-black text-[#1a2535]">24</div>
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Guru Aktif</div>
            </div>
            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#ffd166]"></div>
                <div class="mb-3"><i data-lucide="building" class="w-8 h-8 text-[#ffd166]"></i></div>
                <div class="text-2xl font-black text-[#1a2535]">10</div>
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Total Kelas</div>
            </div>
            <div class="bg-white p-6 rounded-[18px] border border-[#e2e8f0] shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-[4px] bg-[#ef476f]"></div>
                <div class="mb-3"><i data-lucide="pie-chart" class="w-8 h-8 text-[#ef476f]"></i></div>
                <div class="text-2xl font-black text-[#1a2535]">89%</div>
                <div class="text-xs font-bold text-[#90a0b4] uppercase tracking-wider mt-1">Rata-rata Kehadiran</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-[18px] border border-[#e2e8f0] shadow-sm overflow-hidden">
                <div class="p-5 border-b border-[#e2e8f0] bg-white flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#1a2535]"></i>
                    <h3 class="font-bold text-[#1a2535]">Persentase Kehadiran Kelas (Hari Ini)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[11px] font-bold text-[#90a0b4] uppercase tracking-wider border-b border-[#e2e8f0]">
                                <th class="p-4">Nama Kelas</th>
                                <th class="p-4">Hadir</th>
                                <th class="p-4">Total Siswa</th>
                                <th class="p-4">Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-[#1a2535]">
                            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/50">
                                <td class="p-4 font-bold">X-A</td>
                                <td class="p-4">29</td>
                                <td class="p-4">32</td>
                                <td class="p-4 text-[#0cb47a] font-bold">91%</td>
                            </tr>
                            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/50">
                                <td class="p-4 font-bold">X-B</td>
                                <td class="p-4">27</td>
                                <td class="p-4">30</td>
                                <td class="p-4 text-[#0cb47a] font-bold">90%</td>
                            </tr>
                            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/50">
                                <td class="p-4 font-bold">XI-A</td>
                                <td class="p-4">26</td>
                                <td class="p-4">32</td>
                                <td class="p-4 text-[#b07500] font-bold">81%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-[18px] border border-[#e2e8f0] shadow-sm p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <i data-lucide="zap" class="w-5 h-5 text-[#f59e0b]"></i>
                        <h3 class="font-bold text-[#1a2535]">Akses Kilat Manajemen</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="/admin/akun" class="p-4 bg-slate-50 hover:bg-[#0f4c75]/5 border border-[#e2e8f0] rounded-xl flex flex-col items-center justify-center text-center transition-all">
                            <i data-lucide="users" class="w-7 h-7 mb-2 text-[#0f4c75]"></i>
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
                            <i data-lucide="bar-chart-2" class="w-7 h-7 mb-2 text-slate-700"></i>
                            <span class="text-[13px] font-bold text-slate-700">Laporan Sekolah</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>