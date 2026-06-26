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
         class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
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

        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-sm">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
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

                <!-- Clock & Date -->
                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-lg border border-slate-100">
                    <div class="text-right">
                        <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">{{ date('H:i:s') }}</p>
                    </div>
                    <div class="w-[1px] h-4 bg-slate-200"></div>
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
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
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                
                <!-- KOLOM KIRI: PANEL KONTROL -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm p-6 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-amber-500"></div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Konfigurasi Sesi</h3>
                        
                        <div class="space-y-4">
                            <div class="relative group">
                                <select id="jadwal_id" required class="w-full pl-3 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 outline-none focus:border-[#0b1e36] appearance-none transition-all">
                                    <option value="">-- Cari Kelas & Mapel --</option>
                                    @foreach($jadwals as $j)
                                        <option value="{{ $j->id }}" {{ (isset($jadwalAktif) && $jadwalAktif->id == $j->id) ? 'selected' : '' }}>
                                            {{ $j->kelas->nama_kelas }} — {{ $j->mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300 pointer-events-none"></i>
                            </div>

                            @if(!isset($qrImage))
                                <!-- Tombol Buka Sesi (Memanggil triggerError di Alpine) -->
                                <button @click="const id = document.getElementById('jadwal_id').value; id ? window.location.href = '/guru/generate-qr/' + id : triggerError('Silakan pilih jadwal mengajar terlebih dahulu untuk merilis kode QR.')" 
                                        class="w-full h-12 bg-[#0b1e36] hover:bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-xl shadow-lg flex items-center justify-center gap-3 transition-all active:scale-95">
                                    <i data-lucide="radio" class="w-4 h-4 text-white"></i>
                                    Buka Sesi Scan
                                </button>
                            @else
                                <form action="{{ route('guru.stop-qr', $qrId ?? 0) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full h-12 bg-white border-2 border-rose-100 text-rose-600 hover:bg-rose-50 text-[11px] font-black uppercase tracking-[0.2em] rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95">
                                        <i data-lucide="square" class="w-4 h-4 fill-rose-600"></i>
                                        Hentikan Sesi
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-xl p-5 text-white shadow-xl">
                        <h4 class="text-[10px] font-black uppercase tracking-widest opacity-50 mb-3 flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-3 h-3 text-amber-400"></i> Protokol Keamanan
                        </h4>
                        <p class="text-[10px] leading-relaxed opacity-80 italic">
                            Gerbang QR dilindungi enkripsi token dinamis. Pastikan hanya siswa di dalam kelas yang melakukan pemindaian.
                        </p>
                    </div>
                </div>

                <!-- KOLOM KANAN: MONITOR QR -->
                <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden flex flex-col items-center justify-center p-8 min-h-[450px]">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-slate-100"></div>
                    
                    @if(isset($qrImage))
                        <div class="text-center animate-in zoom-in duration-500 w-full max-w-sm">
                            <div class="inline-block p-6 bg-white border border-slate-200 rounded-[2.5rem] shadow-2xl mb-8 relative">
                                <img src="data:image/svg+xml;base64,{{ $qrImage }}" class="w-64 h-64">
                                <span class="absolute top-4 right-4 bg-slate-100 text-slate-400 text-[8px] font-bold px-2 py-1 rounded tracking-tighter uppercase">Encrypted</span>
                            </div>
                            
                            <div class="space-y-1">
                                <h4 class="text-xl font-black text-slate-900 tracking-tight leading-none uppercase">{{ $jadwalAktif->kelas->nama_kelas }}</h4>
                                <p class="text-sm font-bold text-amber-600 uppercase tracking-widest">{{ $jadwalAktif->mapel->nama_mapel }}</p>
                            </div>

                            <div class="mt-8 py-3 px-6 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl flex items-center justify-center gap-3">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                                <span class="text-[11px] font-black uppercase tracking-[0.2em]">Menunggu Pemindaian Siswa...</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center group">
                            <div class="w-24 h-24 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-5 border border-slate-100 group-hover:rotate-6 transition-all duration-500">
                                <i data-lucide="monitor-off" class="w-10 h-10 text-slate-200"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-300">Monitor Standby</p>
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