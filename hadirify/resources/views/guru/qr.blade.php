<x-guru-layout>
    <!-- Container Utama dengan State Alpine.js Baru: showError -->
    <div x-data="{ 
            qrMembuka: false, 
            showError: false, 
            errorMessage: '',
            triggerError(msg) {
                this.errorMessage = msg;
                this.showError = true;
                setTimeout(() => this.showError = false, 5000); // Auto close 5 detik
            }
         }" 
         class="animate-in fade-in duration-700 flex flex-col space-y-3 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- ================= MODAL ERROR CUSTOM (PENGGANTI ALERT) ================= -->
        <template x-teleport="body">
            <div x-show="showError" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4">
                <div @click.away="showError = false"
                     x-show="showError"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     class="bg-white rounded-xl p-8 max-w-sm w-full text-center shadow-2xl border border-rose-100 relative overflow-hidden">
                    
                    <!-- Garis Aksen Atas -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-rose-500"></div>

                    <div class="mx-auto mb-5 flex items-center justify-center w-16 h-16 rounded-full bg-rose-50 text-rose-500">
                        <i data-lucide="alert-circle" class="w-10 h-10 stroke-[2.5px]"></i>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Perhatian!</h3>
                    <p class="text-[13px] font-medium text-slate-500 mt-2 leading-relaxed" x-text="errorMessage"></p>
                    
                    <button @click="showError = false"
                            class="mt-8 w-full py-3 rounded-lg font-black text-[11px] uppercase tracking-widest text-white bg-rose-600 hover:bg-rose-700 transition-all active:scale-95 shadow-lg shadow-rose-100">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </template>

        <!-- ================= SECTION 1: HEADER (FIXED - CLEAN KOTAK) ================= -->
        <div class="flex-none bg-white border border-slate-200/80 rounded-lg shadow-2xs">
            <div class="p-4 sm:px-5 sm:py-3 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0b1e36] flex items-center justify-center rounded-lg shadow-lg">
                        <i data-lucide="qr-code" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Gerbang Absensi QR</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">
                            @if(isset($qrImage))
                                <span class="text-emerald-600 font-bold italic">Sesi sedang berlangsung...</span>
                            @else
                                Siap membuka pintu absensi mandiri siswa.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Clock & Date dengan label WIB jelas -->
                <div class="flex items-center gap-3 px-3.5 py-2 bg-slate-50/80 rounded-xl border border-slate-100 shadow-2xs">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ now()->translatedFormat('d M Y') }}</p>
                        <p class="text-xs font-bold text-[#0b1e36] font-mono leading-none">
                            <span id="realtime-clock">{{ date('H:i:s') }}</span> <span class="text-[10px] text-slate-400 font-sans font-bold">WIB</span>
                        </p>
                    </div>
                    <div class="w-[1px] h-6 bg-slate-200"></div>
                    <i data-lucide="clock" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-2 animate-in zoom-in">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 2: CONTENT (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-2">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
                
                <!-- KOLOM KIRI: PANEL KONTROL (Clean Kotak) -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="bg-white rounded-lg border border-slate-200/80 shadow-2xs p-5">
                        <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-4">Konfigurasi Sesi</h3>
                        
                        <div class="space-y-4">
                            <div class="relative">
                                <select id="jadwal_id" required class="w-full pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-md text-xs font-bold text-slate-700 outline-none focus:border-[#0b1e36] transition-all cursor-pointer">
                                    <option value="">-- Cari Kelas & Mapel --</option>
                                    @foreach($jadwals as $j)
                                        <option value="{{ $j->id }}" {{ (isset($jadwalAktif) && $jadwalAktif->id == $j->id) ? 'selected' : '' }}>
                                            {{ $j->kelas->nama_kelas }} — {{ $j->mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if(!isset($qrImage))
                                <!-- Tombol Buka Sesi (Memanggil triggerError di Alpine) -->
                                <button @click="const id = document.getElementById('jadwal_id').value; id ? window.location.href = '/guru/generate-qr/' + id : triggerError('Silakan pilih jadwal mengajar terlebih dahulu untuk merilis kode QR.')" 
                                        class="w-full h-11 bg-[#0b1e36] hover:bg-slate-800 text-white text-[11px] font-bold uppercase tracking-wider rounded-md shadow-sm flex items-center justify-center gap-2.5 transition-all active:scale-95 cursor-pointer">
                                    <i data-lucide="radio" class="w-4 h-4 text-white"></i>
                                    Buka Sesi Scan
                                </button>
                            @else
                                <form action="{{ route('guru.stop-qr', $qrId ?? 0) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full h-11 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 text-[11px] font-bold uppercase tracking-wider rounded-md flex items-center justify-center gap-2.5 transition-all active:scale-95 cursor-pointer">
                                        <i data-lucide="square" class="w-4 h-4 fill-rose-600"></i>
                                        Hentikan Sesi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="bg-[#0b1e36] rounded-lg p-5 text-white shadow-sm border border-slate-800">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-amber-300 mb-2.5 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-amber-400"></i> Protokol Keamanan
                        </h4>
                        <p class="text-[11px] leading-relaxed text-slate-300">
                            Gerbang QR dilindungi enkripsi token dinamis. Pastikan hanya siswa di dalam kelas yang melakukan pemindaian.
                        </p>
                    </div>
                </div>

                <!-- KOLOM KANAN: MONITOR QR (Clean Kotak) -->
                <div class="lg:col-span-8 bg-white rounded-lg border border-slate-200/80 shadow-2xs flex flex-col items-center justify-center p-6 min-h-[380px]">
                    
                    @if(isset($qrImage))
                        <div class="text-center animate-in zoom-in duration-500 w-full max-w-sm flex flex-col items-center">
                            
                            <!-- Kartu QR Code Bebas Hambatan (Finder pattern tidak tertutup) -->
                            <div class="bg-white border-2 border-slate-200/80 rounded-xl shadow-md p-5 mb-4 w-fit">
                                <div class="flex items-center justify-between pb-3 mb-2 border-b border-slate-100 gap-6">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">QR Presensi</span>
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[9px] font-bold px-2 py-0.5 rounded border border-emerald-200 tracking-wider uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Sesi Aktif
                                    </span>
                                </div>

                                <!-- QR Image: Ukuran Lebih Besar, Kontras Tinggi & Margin Bersih -->
                                <div class="p-2 bg-white rounded-lg flex items-center justify-center">
                                    <img src="data:image/svg+xml;base64,{{ $qrImage }}" class="w-56 h-56 sm:w-64 sm:h-64 object-contain select-none pointer-events-none" alt="QR Code Presensi">
                                </div>

                                @if(isset($qrToken))
                                    <!-- Token Kode Cadangan -->
                                    <div class="mt-2 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2 text-[10px]">
                                        <span class="font-mono text-slate-400 truncate max-w-[170px]" title="{{ $qrToken }}">{{ $qrToken }}</span>
                                        <button type="button" 
                                                onclick="navigator.clipboard.writeText('{{ $qrToken }}'); alert('Kode token sesi presensi berhasil disalin!')" 
                                                class="text-sky-600 hover:text-sky-700 font-bold flex items-center gap-1 cursor-pointer transition-colors" 
                                                title="Salin Kode Token Sesi">
                                            <i data-lucide="copy" class="w-3 h-3"></i> Salin Token
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="space-y-1">
                                <h4 class="text-xl font-black text-slate-900 tracking-tight leading-none uppercase">{{ $jadwalAktif->kelas->nama_kelas }}</h4>
                                <p class="text-sm font-bold text-amber-600 uppercase tracking-widest">{{ $jadwalAktif->mapel->nama_mapel }}</p>
                            </div>

                            <div class="mt-3.5 py-2 px-5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-md flex items-center justify-center gap-3">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-black uppercase tracking-[0.15em]">Menunggu Pemindaian Siswa...</span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-bold mt-2 flex items-center justify-center gap-1.5">
                                <i data-lucide="timer" class="w-3.5 h-3.5 text-amber-500"></i>
                                Sesi Otomatis Ditutup dalam 15 Menit
                            </p>
                        </div>
                    @else
                        <div class="text-center group">
                            <div class="w-20 h-20 bg-slate-50 rounded-lg flex items-center justify-center mx-auto mb-4 border border-slate-100 transition-all duration-300">
                                <i data-lucide="monitor-off" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Monitor Standby</p>
                            <p class="text-[10px] text-slate-400 mt-1">Sinyal gerbang belum diaktifkan.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            const el = document.getElementById('realtime-clock');
            if(el) el.textContent = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ':' + now.getSeconds().toString().padStart(2, '0');
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guru-layout>