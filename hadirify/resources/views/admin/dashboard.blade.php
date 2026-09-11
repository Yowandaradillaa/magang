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

    <div class="space-y-10 pb-12 text-slate-800">
        
        <!-- Banner Utama (Clean White dengan Garis Vertikal Navy #0b1e36 di Kiri) -->
        <!-- Banner Utama (Dengan Warna Biru Navy Soft yang Hidup) -->
<div class="bg-gradient-to-r from-slate-100 via-sky-50/50 to-white p-6 md:p-8 rounded-2xl border border-slate-200/80 border-l-4 border-l-[#0b1e36] flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div class="space-y-1.5">
                <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                    <i data-lucide="school" class="w-4 h-4 text-[#0b1e36]"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0b1e36] tracking-tight">
                    Dashboard Admin
                </h1>
                <p class="text-sm text-slate-500 font-normal">
                    Selamat datang, <span class="font-semibold text-slate-800">{{ Auth::user()->name }}</span>. Monitoring operasional presensi harian sekolah.
                </p>
            </div>

            <!-- Jam & Tanggal Real-time -->
            <div class="text-left md:text-right shrink-0">
                <p id="realtime-date" class="text-xs font-medium text-slate-400 mb-0.5">
                    {{ date('d F Y') }}
                </p>
                <p id="realtime-clock" class="text-xl font-bold text-[#0b1e36] font-mono">
                    {{ date('H:i:s') }} WIB
                </p>
            </div>
        </div>

        <!-- Akses Pintas Navigasi Modul (Clean Minimalist Pills) -->
        <div class="flex flex-wrap items-center gap-2.5">
            <span class="text-xs font-semibold text-slate-400 mr-1">Navigasi Cepat:</span>
            <a href="/admin/akun" class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-medium text-slate-600 hover:text-[#0b1e36] hover:border-slate-300 transition-colors inline-flex items-center gap-2">
                <i data-lucide="users" class="w-3.5 h-3.5 text-slate-400"></i> Kelola Akun
            </a>
            <a href="/admin/kelas" class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-medium text-slate-600 hover:text-[#0b1e36] hover:border-slate-300 transition-colors inline-flex items-center gap-2">
                <i data-lucide="building" class="w-3.5 h-3.5 text-slate-400"></i> Kelas & Jadwal
            </a>
            <a href="/admin/koreksi" class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-medium text-slate-600 hover:text-[#0b1e36] hover:border-slate-300 transition-colors inline-flex items-center gap-2">
                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-slate-400"></i> Koreksi Absen
            </a>
            <a href="/admin/laporan" class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-medium text-slate-600 hover:text-[#0b1e36] hover:border-slate-300 transition-colors inline-flex items-center gap-2">
                <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-slate-400"></i> Laporan Sekolah
            </a>
            <a href="/admin/mapel" class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-medium text-slate-600 hover:text-[#0b1e36] hover:border-slate-300 transition-colors inline-flex items-center gap-2">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-slate-400"></i> Mata Pelajaran
            </a>
        </div>

        <!-- Kartu Statistik (Minimalis Tanpa Ikon Kotak Berlebihan) -->
        <!-- Kartu Statistik (Warna Lebih Hidup & Tegas) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Kartu 1: Total Siswa (Biru) -->
            <div class="bg-sky-100/70 p-6 rounded-2xl border border-sky-200 shadow-sm space-y-2">
                <p class="text-sm font-bold text-sky-900">Total Siswa</p>
                <div class="text-4xl font-extrabold text-[#0b1e36] tracking-tight">{{ number_format($totalSiswa) }}</div>
                <p class="text-xs text-sky-800 font-medium">Siswa aktif terdaftar di database</p>
            </div>

            <!-- Kartu 2: Total Guru (Indigo / Ungu) -->
            <div class="bg-indigo-100/70 p-6 rounded-2xl border border-indigo-200 shadow-sm space-y-2">
                <p class="text-sm font-bold text-indigo-900">Total Guru</p>
                <div class="text-4xl font-extrabold text-indigo-950 tracking-tight">{{ number_format($totalGuru) }}</div>
                <p class="text-xs text-indigo-800 font-medium">Tenaga pendidik dan staf pengajar</p>
            </div>

            <!-- Kartu 3: Kehadiran Hari Ini (Emerald / Hijau) -->
            <div class="bg-emerald-100/70 p-6 rounded-2xl border border-emerald-200 shadow-sm space-y-2">
                <p class="text-sm font-bold text-emerald-900">Kehadiran Hari Ini</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-emerald-700 tracking-tight">{{ number_format($totalHadir) }}</span>
                    <span class="text-sm font-medium text-emerald-800">/ {{ $totalSiswa }} siswa</span>
                </div>
                <p class="text-xs text-emerald-800 font-medium">
                    Rasio kehadiran: <span class="font-bold text-emerald-900">{{ $persenHadir }}%</span> (Izin/Sakit: {{ $totalIzin + $totalSakit }})
                </p>
            </div>

        </div>

        <!-- Tabel Aktivitas Presensi Terbaru (Natural & Clean Layout) -->
        <div class="bg-white rounded-2xl border border-slate-200/70 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-[#0b1e36]">Aktivitas Presensi Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Catatan kehadiran siswa terkini yang terhubung ke sistem</p>
                </div>
                <a href="/admin/laporan" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#0b1e36] hover:underline">
                    Lihat Laporan Lengkap <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/60 border-b border-slate-100 text-xs font-semibold text-slate-500">
                            <th class="py-3.5 px-6">Nama Siswa</th>
                            <th class="py-3.5 px-4">Kelas</th>
                            <th class="py-3.5 px-4">Waktu Absen</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Metode</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($recentAbsensi as $absensi)
                            <tr class="hover:bg-slate-50/40 transition-colors">
                                <td class="py-3.5 px-6 font-semibold text-slate-800">
                                    {{ $absensi->siswa->name ?? 'Siswa' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 font-medium">
                                    {{ $absensi->siswa->kelas->nama_kelas ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 font-mono">
                                    {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                    @if($absensi->waktu_absen)
                                        <span class="text-slate-400 font-normal">({{ \Carbon\Carbon::parse($absensi->waktu_absen)->format('H:i') }})</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($absensi->status === 'H')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">Hadir</span>
                                    @elseif($absensi->status === 'I')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-50 text-sky-700 border border-sky-200/60">Izin</span>
                                    @elseif($absensi->status === 'S')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200/60">Sakit</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">Alpa</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 capitalize">
                                    {{ $absensi->metode ?? 'Sistem' }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <a href="/admin/koreksi" class="text-slate-400 hover:text-[#0b1e36] font-medium inline-flex items-center gap-1 text-xs">
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