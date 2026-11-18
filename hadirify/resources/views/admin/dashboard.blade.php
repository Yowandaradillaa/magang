<x-admin-layout>
    @php
        $totalHadir = $stats['hadir_hari_ini'] ?? 0;
        $totalIzin  = $stats['izin_hari_ini'] ?? 0;
        $totalSakit = $stats['sakit_hari_ini'] ?? 0;
        $totalAlpa  = $stats['alpa_hari_ini'] ?? 0;
        $totalSiswa = $stats['total_siswa'] ?? 0;
        $totalGuru  = $stats['total_guru'] ?? 0;
        
        $persenHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;

        // Ambil 7 presensi terbaru untuk tabel aktivitas
        $recentAbsensi = \App\Models\Absensi::with(['siswa.kelas'])->latest('updated_at')->take(7)->get();
    @endphp

    <div class="space-y-6 pb-8 text-slate-800">
        
        <!-- Hero Banner Utama -->
        <div class="bg-gradient-to-r from-[#0b1e36] via-[#122c4f] to-[#183b66] text-white p-5 md:p-6 rounded-lg shadow-2xs relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-sky-400/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="space-y-1.5 relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/10 border border-white/15 text-[11px] font-semibold text-sky-200">
                    <i data-lucide="school" class="w-3.5 h-3.5 text-sky-300"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </span>
                <h1 class="text-xl md:text-2xl font-extrabold tracking-tight text-white">
                    Dashboard Admin
                </h1>
                
            </div>

            <!-- Jam & Tanggal Real-time -->
            <div class="relative z-10 bg-white/10 backdrop-blur-md border border-white/15 rounded-md px-4 py-2.5 text-left md:text-right shrink-0">
                <p id="realtime-date" class="text-[11px] font-medium text-sky-200 mb-0.5">
                    {{ date('d F Y') }}
                </p>
                <p id="realtime-clock" class="text-lg md:text-xl font-bold text-white font-mono leading-tight">
                    {{ date('H:i:s') }} WIB
                </p>
            </div>
        </div>

        <!-- Akses Pintas Navigasi Modul -->
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold text-slate-500 mr-1">Navigasi Cepat:</span>
            <a href="/admin/akun" class="px-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-semibold text-slate-600 hover:text-[#0b1e36] hover:border-sky-300 hover:bg-sky-50/40 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="users" class="w-3.5 h-3.5 text-sky-600"></i> Kelola Akun
            </a>
            <a href="/admin/kelas" class="px-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-semibold text-slate-600 hover:text-[#0b1e36] hover:border-sky-300 hover:bg-sky-50/40 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="building" class="w-3.5 h-3.5 text-indigo-600"></i> Kelas & Jadwal
            </a>
            <a href="/admin/koreksi" class="px-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-semibold text-slate-600 hover:text-[#0b1e36] hover:border-sky-300 hover:bg-sky-50/40 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600"></i> Koreksi Absen
            </a>
            <a href="/admin/laporan" class="px-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-semibold text-slate-600 hover:text-[#0b1e36] hover:border-sky-300 hover:bg-sky-50/40 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-emerald-600"></i> Laporan Sekolah
            </a>
            <a href="/admin/mapel" class="px-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-semibold text-slate-600 hover:text-[#0b1e36] hover:border-sky-300 hover:bg-sky-50/40 transition-all inline-flex items-center gap-1.5 shadow-2xs">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-slate-600"></i> Mata Pelajaran
            </a>
        </div>

        <!-- Kartu Statistik Seragam -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Kartu 1: Total Siswa -->
            <div class="bg-white p-5 rounded-md border border-slate-200/80 shadow-2xs space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Siswa</p>
                    <div class="w-9 h-9 rounded-md bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center">
                        <i data-lucide="graduation-cap" class="w-[18px] h-[18px]"></i>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-[#0b1e36] tracking-tight">{{ number_format($totalSiswa) }}</div>
                <p class="text-xs text-slate-500 font-medium">Siswa aktif terdaftar di sistem</p>
            </div>

            <!-- Kartu 2: Total Guru -->
            <div class="bg-white p-5 rounded-md border border-slate-200/80 shadow-2xs space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Guru</p>
                    <div class="w-9 h-9 rounded-md bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center">
                        <i data-lucide="briefcase" class="w-[18px] h-[18px]"></i>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-[#0b1e36] tracking-tight">{{ number_format($totalGuru) }}</div>
                <p class="text-xs text-slate-500 font-medium">Tenaga pendidik & staf pengajar</p>
            </div>

            <!-- Kartu 3: Kehadiran Hari Ini -->
            <div class="bg-white p-5 rounded-md border border-slate-200/80 shadow-2xs space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kehadiran Hari Ini</p>
                    <div class="w-9 h-9 rounded-md bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center">
                        <i data-lucide="user-check" class="w-[18px] h-[18px]"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ number_format($totalHadir) }}</span>
                    <span class="text-xs font-semibold text-slate-400">/ {{ $totalSiswa }} siswa</span>
                </div>
                <p class="text-xs text-slate-500 font-medium">
                    Rasio: <span class="px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold">{{ $persenHadir }}%</span>
                    • Izin/Sakit: {{ $totalIzin + $totalSakit }}
                </p>
            </div>
        </div>

        <!-- Tabel Aktivitas Presensi Terbaru -->
        <div class="bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-md bg-slate-100 text-[#0b1e36] flex items-center justify-center shrink-0">
                        <i data-lucide="activity" class="w-[18px] h-[18px]"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-[#0b1e36]">Aktivitas Presensi Terbaru</h2>
                        <p class="text-xs text-slate-500">Catatan kehadiran siswa terkini yang terhubung ke sistem</p>
                    </div>
                </div>
                <a href="/admin/laporan" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-slate-50 hover:bg-slate-100 border border-slate-200 text-xs font-semibold text-[#0b1e36] transition-colors">
                    Lihat Laporan Lengkap <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3 px-5">Nama Siswa</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4">Waktu Absen</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentAbsensi as $absensi)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-3 px-5 font-semibold text-slate-800">
                                    {{ $absensi->siswa->name ?? 'Siswa' }}
                                </td>
                                <td class="py-3 px-4 text-slate-600 font-medium">
                                    {{ $absensi->siswa->kelas->nama_kelas ?? '-' }}
                                </td>
                                <td class="py-3 px-4 text-slate-500 font-mono">
                                    {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                    @if($absensi->waktu_absen)
                                        <span class="text-slate-400 font-normal">({{ \Carbon\Carbon::parse($absensi->waktu_absen)->format('H:i') }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($absensi->status === 'H')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">Hadir</span>
                                    @elseif($absensi->status === 'I')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-sky-50 text-sky-700 border border-sky-200/70">Izin</span>
                                    @elseif($absensi->status === 'S')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200/70">Sakit</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/70">Alpa</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-slate-500 capitalize">
                                    {{ $absensi->metode ?? 'Sistem' }}
                                </td>
                                <td class="py-3 px-5 text-right">
                                    <a href="/admin/koreksi" class="text-slate-500 hover:text-[#0b1e36] font-semibold inline-flex items-center gap-1 text-xs">
                                        <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                    <p class="text-xs font-medium">Belum ada aktivitas presensi yang tercatat hari ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            const options = { day: '2-digit', month: 'long', year: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const timeStr = `${hours}:${minutes}:${seconds}`;

            const dateEl = document.getElementById('realtime-date');
            const clockEl = document.getElementById('realtime-clock');

            if (dateEl) dateEl.textContent = dateStr;
            if (clockEl) clockEl.textContent = `${timeStr} WIB`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
</x-admin-layout>