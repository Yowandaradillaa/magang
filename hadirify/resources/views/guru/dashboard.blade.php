<x-guru-layout>
    <div x-data="{ qrMembuka: false, kelasAktif: '' }" class="animate-in fade-in duration-500 space-y-8 pb-12 text-slate-800">
        
        <!-- Header Banner (Clean White dengan Garis Vertikal Navy #0b1e36 di Kiri) -->
        <!-- Header Banner (Dengan Warna Soft Blue-Grey yang Hidup & Elegan) -->
<div class="bg-gradient-to-r from-slate-100 via-sky-50/40 to-white p-6 md:p-8 rounded-2xl border border-slate-200/80 border-l-4 border-l-[#0b1e36] flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm">
            <div class="space-y-1.5">
                <div class="text-xs font-semibold text-slate-500 flex items-center gap-1.5">
                    <i data-lucide="school" class="w-4 h-4 text-[#0b1e36]"></i>
                    <span>SMA Muhammadiyah 7 Yogyakarta</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#0b1e36] tracking-tight">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-sm text-slate-500 font-normal">
                    Ruang Kendali Pendidik — Pemantauan presensi dan pengelolaan kelas harian.
                </p>
            </div>

            <!-- Jam & Tanggal Real-time -->
            <div class="text-left md:text-right shrink-0">
                <p id="realtime-date" class="text-xs font-medium text-slate-400 mb-0.5">
                    {{ now()->translatedFormat('d F Y') }}
                </p>
                <p id="realtime-clock" class="text-xl font-bold text-[#0b1e36] font-mono">
                    {{ date('H:i:s') }} WIB
                </p>
            </div>
        </div>

        <!-- Kartu Statistik Presensi (Minimalis & Proporsional) -->
        <!-- Kartu Statistik Presensi (Dengan Warna Lembut & Hidup) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $guruStats = [
                    ['label' => 'Siswa Hadir', 'val' => $stats['hadir'], 'color' => 'bg-emerald-50/70 border-emerald-200 text-emerald-900', 'sub' => 'text-emerald-700'],
                    ['label' => 'Izin Siswa', 'val' => $stats['izin'], 'color' => 'bg-sky-50/70 border-sky-200 text-sky-900', 'sub' => 'text-sky-700'],
                    ['label' => 'Sakit', 'val' => $stats['sakit'], 'color' => 'bg-indigo-50/70 border-indigo-200 text-indigo-900', 'sub' => 'text-indigo-700'],
                    ['label' => 'Alpa', 'val' => $stats['alpa'], 'color' => 'bg-rose-50/70 border-rose-200 text-rose-900', 'sub' => 'text-rose-700'],
                ];
            @endphp
            
            @foreach($guruStats as $s)
                <div class="{{ $s['color'] }} p-5 rounded-2xl border shadow-xs space-y-1.5 transition-all">
                    <p class="text-xs font-semibold opacity-80">{{ $s['label'] }}</p>
                    <div class="text-3xl font-extrabold tracking-tight">
                        {{ number_format($s['val']) }}
                        <span class="text-xs font-normal opacity-70">siswa</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Kendali Presensi Kelas (Quick Access Cards) -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-[#0b1e36]">Kendali Presensi Kelas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Presensi Manual -->
                <a href="/guru/manual" class="group p-6 bg-white border border-slate-200/70 rounded-2xl hover:border-slate-300 hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center group-hover:bg-[#0b1e36] group-hover:text-white transition-colors duration-200 shrink-0">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 group-hover:text-[#0b1e36] transition-colors mb-1">Input Presensi Manual</h3>
                        <p class="text-xs text-slate-500 font-normal">Catat status kehadiran siswa secara langsung per mata pelajaran.</p>
                    </div>
                </a>

                <!-- QR Code -->
                <a href="/guru/qr" class="group p-6 bg-white border border-slate-200/70 rounded-2xl hover:border-slate-300 hover:shadow-md transition-all flex items-center gap-5">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-200 shrink-0">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800 group-hover:text-amber-600 transition-colors mb-1">Rilis QR Code Presensi</h3>
                        <p class="text-xs text-slate-500 font-normal">Tampilkan kode QR dinamis di layar untuk dipindai oleh siswa.</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Jadwal Mengajar Hari Ini -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-[#0b1e36]">Jadwal Mengajar Hari Ini</h3>
                <span class="text-xs text-slate-400 font-normal">{{ count($jadwalHariIni) }} Sesi Terjadwal</span>
            </div>
            
            <div class="space-y-3">
                @forelse($jadwalHariIni as $j)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-slate-50/70 rounded-xl border border-slate-200/60">
                        <div>
                            <h4 class="font-bold text-slate-800 text-sm">{{ $j->mapel->nama_mapel }} — Kelas {{ $j->kelas->nama_kelas }}</h4>
                            <p class="text-xs text-slate-500 font-mono mt-0.5">Jam: {{ $j->jam_mulai }} - {{ $j->jam_selesai }} WIB</p>
                        </div>
                        <a href="{{ route('guru.manual') }}?jadwal_id={{ $j->id }}" class="px-4 py-2 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all text-center shrink-0">
                            Buka Absensi
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-6">Tidak ada jadwal mengajar untuk hari ini.</p>
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