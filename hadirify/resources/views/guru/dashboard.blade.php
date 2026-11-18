<x-guru-layout>
    <div x-data="{ 
            qrMembuka: false, 
            kelasAktif: '',
            showDetailModal: false,
            activeTab: 'hadir',
            searchQuery: '',
            detailSiswa: @js($detailSiswa ?? ['hadir' => [], 'izin' => [], 'sakit' => [], 'alpa' => []]),
            openTab(tab) {
                this.activeTab = tab;
                this.showDetailModal = true;
                this.searchQuery = '';
                this.$nextTick(() => { 
                    if (window.lucide) lucide.createIcons(); 
                });
            },
            filteredList() {
                const list = this.detailSiswa[this.activeTab] || [];
                if (!this.searchQuery) return list;
                const q = this.searchQuery.toLowerCase();
                return list.filter(s => 
                    s.nama.toLowerCase().includes(q) || 
                    s.kelas.toLowerCase().includes(q) || 
                    s.mapel.toLowerCase().includes(q) ||
                    (s.nisn && s.nisn.includes(q))
                );
            }
         }" 
         class="animate-in fade-in duration-500 space-y-6 pb-8 text-slate-800">
        
        <!-- ================= MODAL DETAIL SISWA BERDASARKAN STATUS (KOTAK BERWIBAWA) ================= -->
        <template x-teleport="body">
            <div x-show="showDetailModal" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-[#0b1e36]/60 backdrop-blur-sm px-4 py-6 transition-all">
                <div @click.away="showDetailModal = false"
                     x-show="showDetailModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-xl shadow-2xl border border-slate-100 max-w-2xl w-full flex flex-col max-h-[85vh] overflow-hidden relative">
                    
                    <!-- Modal Header -->
                    <div class="p-5 sm:px-6 border-b border-slate-100 flex items-center justify-between bg-white relative z-10 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-[#0b1e36] text-white flex items-center justify-center shrink-0 shadow-sm">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-extrabold text-[#0b1e36] tracking-tight">Rincian Presensi Siswa Hari Ini</h3>
                                <p class="text-xs text-slate-500 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                        <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Tabs Nav & Search Bar -->
                    <div class="px-5 sm:px-6 py-3 bg-slate-50 border-b border-slate-200/80 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 shrink-0">
                        <!-- Status Tabs -->
                        <div class="flex items-center gap-1.5 p-1 bg-slate-200/70 rounded-lg text-xs font-bold">
                            <button @click="openTab('hadir')" 
                                    :class="activeTab === 'hadir' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Hadir</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800" x-text="detailSiswa.hadir.length"></span>
                            </button>
                            <button @click="openTab('izin')" 
                                    :class="activeTab === 'izin' ? 'bg-white text-sky-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Izin</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-100 text-sky-800" x-text="detailSiswa.izin.length"></span>
                            </button>
                            <button @click="openTab('sakit')" 
                                    :class="activeTab === 'sakit' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Sakit</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-indigo-100 text-indigo-800" x-text="detailSiswa.sakit.length"></span>
                            </button>
                            <button @click="openTab('alpa')" 
                                    :class="activeTab === 'alpa' ? 'bg-white text-rose-700 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                                    class="px-3 py-1.5 rounded-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <span>Alpa</span>
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-rose-100 text-rose-800" x-text="detailSiswa.alpa.length"></span>
                            </button>
                        </div>

                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" 
                                   x-model="searchQuery" 
                                   placeholder="Cari nama, kelas, atau mapel..." 
                                   class="w-full sm:w-60 pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs font-medium text-slate-800 placeholder:text-slate-400 outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 transition-all">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-1/2 -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- List Content (Scrollable) -->
                    <div class="p-5 sm:px-6 overflow-y-auto flex-1 space-y-2.5">
                        <template x-for="(siswa, idx) in filteredList()" :key="idx">
                            <div class="p-3.5 bg-white hover:bg-slate-50 border border-slate-200/80 rounded-lg transition-colors flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-3 min-w-0">
                                    <!-- Initials Avatar -->
                                    <div class="w-9 h-9 rounded-lg bg-[#0b1e36]/5 border border-slate-200 flex items-center justify-center font-black text-xs text-[#0b1e36] shrink-0" 
                                         x-text="siswa.nama.split(' ').map(n=>n[0]).slice(0,2).join('')">
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-xs font-extrabold text-slate-900 truncate" x-text="siswa.nama"></h4>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                            <span class="font-bold text-slate-600" x-text="'Kelas ' + siswa.kelas"></span>
                                            <span>•</span>
                                            <span x-text="siswa.mapel"></span>
                                            <template x-if="siswa.nisn && siswa.nisn !== '-'">
                                                <span class="hidden sm:inline font-mono text-slate-400" x-text="'(NISN: ' + siswa.nisn + ')'"></span>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Time & Badge -->
                                <div class="text-right shrink-0 flex flex-col items-end gap-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider"
                                          :class="{
                                              'bg-emerald-50 text-emerald-700 border border-emerald-200': activeTab === 'hadir',
                                              'bg-sky-50 text-sky-700 border border-sky-200': activeTab === 'izin',
                                              'bg-indigo-50 text-indigo-700 border border-indigo-200': activeTab === 'sakit',
                                              'bg-rose-50 text-rose-700 border border-rose-200': activeTab === 'alpa'
                                          }">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="{
                                                  'bg-emerald-500': activeTab === 'hadir',
                                                  'bg-sky-500': activeTab === 'izin',
                                                  'bg-indigo-500': activeTab === 'sakit',
                                                  'bg-rose-500': activeTab === 'alpa'
                                              }"></span>
                                        <span x-text="siswa.status"></span>
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span x-text="siswa.jam"></span>
                                    </span>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="filteredList().length === 0" class="py-12 text-center text-slate-400 space-y-2">
                            <i data-lucide="user-x" class="w-10 h-10 mx-auto opacity-30 text-slate-400"></i>
                            <p class="text-xs font-bold text-slate-600">Tidak ada data siswa untuk kategori ini</p>
                            <p class="text-[11px] text-slate-400">Belum ada siswa yang tercatat dengan status <span class="font-bold uppercase" x-text="activeTab"></span> pada hari ini.</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 sm:px-6 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3 shrink-0">
                        <span class="text-[11px] text-slate-400 font-medium">
                            Menampilkan <strong class="text-slate-700" x-text="filteredList().length"></strong> siswa
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="/guru/rekap" class="px-3.5 py-2 bg-white hover:bg-slate-100 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 transition-colors">
                                Rekap Lengkap
                            </a>
                            <button @click="showDetailModal = false" class="px-4 py-2 bg-[#0b1e36] hover:bg-slate-800 text-white rounded-lg text-xs font-bold transition-all active:scale-95 cursor-pointer">
                                Tutup
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </template>
        
        <!-- Hero Banner Utama (Clean Kotak Berwibawa) -->
        <div class="bg-[#0b1e36] text-white p-5 md:p-6 rounded-lg border border-slate-800 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1.5 relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/10 border border-white/15 text-[11px] font-semibold text-amber-200">
                    <i data-lucide="school" class="w-3.5 h-3.5 text-amber-300"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </span>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-white">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-xs md:text-sm text-slate-300 font-normal">
                    Ruang Kendali Pendidik — Pemantauan presensi dan pengelolaan kelas harian.
                </p>
            </div>

            <!-- Jam & Tanggal Real-time -->
            <div class="relative z-10 bg-white/10 border border-white/15 rounded-md px-4 py-2 text-left md:text-right shrink-0">
                <p id="realtime-date" class="text-[11px] font-medium text-amber-200 mb-0.5">
                    {{ now()->translatedFormat('d F Y') }}
                </p>
                <p id="realtime-clock" class="text-lg md:text-xl font-bold text-white font-mono leading-tight">
                    {{ date('H:i:s') }} WIB
                </p>
            </div>
        </div>

        <!-- Kartu Statistik Presensi Interaktif (Bisa Diklik untuk Melihat Daftar Siswa) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $guruStats = [
                    ['key' => 'hadir', 'label' => 'Siswa Hadir', 'val' => $stats['hadir'], 'icon' => 'user-check', 'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'valColor' => 'text-emerald-600'],
                    ['key' => 'izin',  'label' => 'Izin Siswa',  'val' => $stats['izin'],  'icon' => 'file-text',  'badge' => 'bg-sky-50 text-sky-600 border-sky-100',       'valColor' => 'text-[#0b1e36]'],
                    ['key' => 'sakit', 'label' => 'Sakit',       'val' => $stats['sakit'], 'icon' => 'heart-pulse','badge' => 'bg-indigo-50 text-indigo-600 border-indigo-100','valColor' => 'text-[#0b1e36]'],
                    ['key' => 'alpa',  'label' => 'Alpa',        'val' => $stats['alpa'],  'icon' => 'user-x',     'badge' => 'bg-rose-50 text-rose-600 border-rose-100',   'valColor' => 'text-rose-600'],
                ];
            @endphp
            
            @foreach($guruStats as $s)
                <div @click="openTab('{{ $s['key'] }}')" 
                     class="bg-white p-5 rounded-lg border border-slate-200/90 shadow-2xs hover:border-sky-500 hover:shadow-md transition-all space-y-2 cursor-pointer group active:scale-[0.99] relative overflow-hidden">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider group-hover:text-sky-600 transition-colors">{{ $s['label'] }}</p>
                        <div class="w-8 h-8 rounded-md border {{ $s['badge'] }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                            <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="flex items-baseline justify-between">
                        <div class="text-2xl md:text-3xl font-extrabold {{ $s['valColor'] }} tracking-tight">
                            {{ number_format($s['val']) }}
                            <span class="text-xs font-medium text-slate-400">siswa</span>
                        </div>
                        <span class="text-[10px] font-bold text-sky-600 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-0.5">
                            Lihat <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kendali Presensi Kelas (Quick Access Cards - Kotak Rapi) -->
        <div class="space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Kendali Presensi Kelas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Presensi Manual -->
                <a href="/guru/manual" class="group p-5 bg-white border border-slate-200/90 rounded-md shadow-2xs hover:border-sky-500 hover:shadow-sm transition-all flex items-center gap-4">
                    <div class="w-10 h-10 bg-sky-50 text-sky-600 border border-sky-100 rounded-md flex items-center justify-center group-hover:bg-[#0b1e36] group-hover:text-white group-hover:border-[#0b1e36] transition-colors duration-200 shrink-0">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 group-hover:text-[#0b1e36] transition-colors">Input Presensi Manual</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Catat status kehadiran siswa secara langsung per mata pelajaran.</p>
                    </div>
                </a>

                <!-- QR Code -->
                <a href="/guru/qr" class="group p-5 bg-white border border-slate-200/90 rounded-md shadow-2xs hover:border-sky-500 hover:shadow-sm transition-all flex items-center gap-4">
                    <div class="w-10 h-10 bg-amber-50 text-amber-600 border border-amber-100 rounded-md flex items-center justify-center group-hover:bg-[#0b1e36] group-hover:text-white group-hover:border-[#0b1e36] transition-colors duration-200 shrink-0">
                        <i data-lucide="qr-code" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800 group-hover:text-[#0b1e36] transition-colors">Rilis QR Code Presensi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Tampilkan kode QR dinamis di layar untuk dipindai oleh siswa.</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Jadwal Mengajar Hari Ini -->
        <div class="bg-white p-5 rounded-md border border-slate-200/90 shadow-2xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-md bg-slate-100 text-[#0b1e36] flex items-center justify-center">
                        <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-sm font-bold text-[#0b1e36]">Jadwal Mengajar Hari Ini</h3>
                </div>
                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-[11px] font-bold">{{ count($jadwalHariIni) }} Sesi Terjadwal</span>
            </div>
            
            <div class="space-y-2.5">
                @forelse($jadwalHariIni as $j)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3.5 bg-slate-50/80 hover:bg-slate-100/60 transition-colors rounded-xl border border-slate-200/60">
                        <div>
                            <h4 class="font-bold text-slate-800 text-xs md:text-sm">{{ $j->mapel->nama_mapel }} — Kelas {{ $j->kelas->nama_kelas }}</h4>
                            <p class="text-xs text-slate-500 font-mono mt-0.5 flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                {{ $j->jam_mulai }} - {{ $j->jam_selesai }} WIB
                            </p>
                        </div>
                        <a href="{{ route('guru.manual') }}?jadwal_id={{ $j->id }}" class="px-4 py-2 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-sm transition-all text-center shrink-0">
                            Buka Absensi
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 space-y-1">
                        <i data-lucide="calendar-x" class="w-8 h-8 mx-auto opacity-40"></i>
                        <p class="text-xs font-medium">Tidak ada jadwal mengajar untuk hari ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeStr = `${hours}:${minutes}:${seconds}`;
            
            const clockElement = document.getElementById('realtime-clock');
            if(clockElement) clockElement.textContent = `${timeStr} WIB`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-guru-layout>
