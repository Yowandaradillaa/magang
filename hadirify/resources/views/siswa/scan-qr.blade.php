<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Container Utama: Fixed Header & Scrollable Content -->
    <div x-data="scannerApp()" class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-[0_2px_4px_rgba(0,0,0,0.02)]">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-sky-600 flex items-center justify-center rounded-lg shadow-lg">
                        <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight leading-none">Pemindai Absensi</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic" x-text="isScanning ? 'Mencari sinyal QR...' : 'Arahkan kamera ke QR depan kelas'"></p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-lg border border-slate-100">
                    <div class="flex items-center gap-2 pr-3 border-r border-slate-200">
                        <span class="relative flex h-2 w-2">
                            <span :class="isScanning ? 'bg-sky-500' : 'bg-emerald-500'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                            <span :class="isScanning ? 'bg-sky-500' : 'bg-emerald-500'" class="relative inline-flex rounded-full h-2 w-2"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-500" x-text="isScanning ? 'Scanning' : 'Ready'"></span>
                    </div>
                    <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">00:00:00</p>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 2: CONTENT (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10 space-y-4">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI: SCANNER (Accent Sky) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-white p-5 rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-sky-600"></div>
                        
                        <div @click="startScanning()" class="group relative flex h-[380px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-slate-200 bg-slate-900 transition-all hover:border-sky-500">
                            <!-- Reader Element -->
                            <div id="reader" class="absolute inset-0 w-full h-full z-0 overflow-hidden object-cover" x-show="isScanning"></div>

                            <!-- Corners -->
                            <div class="absolute z-10 left-5 top-5 h-8 w-8 border-l-4 border-t-4 border-sky-500 rounded-tl-lg"></div>
                            <div class="absolute z-10 right-5 top-5 h-8 w-8 border-r-4 border-t-4 border-sky-500 rounded-tr-lg"></div>
                            <div class="absolute z-10 bottom-5 left-5 h-8 w-8 border-b-4 border-l-4 border-sky-500 rounded-bl-lg"></div>
                            <div class="absolute z-10 bottom-5 right-5 h-8 w-8 border-b-4 border-r-4 border-sky-500 rounded-br-lg"></div>

                            <!-- Idle UI -->
                            <div x-show="!isScanning" class="z-10 text-center">
                                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl group-hover:scale-110 transition-transform">
                                    <i data-lucide="maximize" class="w-8 h-8 text-sky-600"></i>
                                </div>
                                <h3 class="text-sm font-black text-white uppercase tracking-widest">Ketuk Untuk Memindai</h3>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tighter">Aktifkan kamera gawai Anda</p>
                            </div>

                            <!-- Laser Scan Line -->
                            <div x-show="isScanning" class="absolute z-20 left-0 w-full h-[2px] bg-sky-400 top-0 animate-[bounce_4s_infinite] shadow-[0_0_15px_#38bdf8]"></div>
                        </div>

                        <!-- GPS Info Bar -->
                        <div class="mt-4 flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div :class="jarakMeter === null ? 'bg-slate-200 text-slate-400' : (gpsValid ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600')" class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Validasi Lokasi</p>
                                    <p class="text-[11px] font-bold text-slate-700 mt-1" x-text="jarakMeter === null ? 'GPS belum terhubung' : (gpsValid ? 'Area Sekolah ('+jarakMeter+'m)' : 'Di luar jangkauan ('+jarakMeter+'m)')"></p>
                                </div>
                            </div>
                            <button @click="cekLokasi()" class="px-3 py-1.5 bg-white border border-slate-200 text-[9px] font-black uppercase text-slate-600 rounded-md hover:bg-slate-100 active:scale-95 transition-all">
                                Perbarui GPS
                            </button>
                        </div>
                    </div>

                    <!-- Status Notification -->
                    <div x-show="status !== null" x-transition 
                         :class="status?.type === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-rose-50 border-rose-100 text-rose-700'"
                         class="p-4 rounded-xl border flex gap-3 items-center animate-in zoom-in duration-300">
                        <i data-lucide="info" class="w-4 h-4 shrink-0"></i>
                        <span class="text-xs font-bold uppercase tracking-tight" x-text="status?.msg"></span>
                    </div>
                </div>

                <!-- KOLOM KANAN: JADWAL (Accent Navy) -->
                <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden flex flex-col">
                    <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-[#0b1e36]"></div>
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i data-lucide="calendar-check" class="w-4 h-4 text-slate-400"></i>
                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Sesi Hari Ini</h3>
                    </div>

                    <div class="divide-y divide-slate-50 overflow-y-auto">
                        <!-- DATA DINAMIS JADWAL -->
                        @forelse($jadwalHariIni ?? [] as $jadwal)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-tight leading-none">{{ $jadwal->mapel->nama_mapel }}</h4>
                                <!-- Badge Status Berdasarkan Tabel Absensi -->
                                @php
                                    $sudahAbsen = $jadwal->absensis->where('siswa_id', Auth::id())->where('tanggal', now()->toDateString())->first();
                                @endphp
                                
                                @if($sudahAbsen)
                                    <span class="text-[8px] font-black px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded uppercase">Hadir</span>
                                @else
                                    <span class="text-[8px] font-black px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded uppercase animate-pulse">Belum Scan</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-slate-400">
                                <span class="text-[10px] font-bold flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                <span class="text-[10px] font-bold flex items-center gap-1"><i data-lucide="user" class="w-3 h-3"></i> {{ $jadwal->guru->name }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-20 text-center opacity-30 flex flex-col items-center">
                            <i data-lucide="calendar-x" class="w-10 h-10 mb-2 text-slate-300"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest italic">Tidak Ada Jadwal</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function scannerApp() {
            return {
                gpsValid: false, 
                status: null,
                isScanning: false,
                isCheckingLocation: false,
                jarakMeter: null,
                userLat: null,
                userLng: null,
                
                cekLokasi() {
                    this.isCheckingLocation = true;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((pos) => {
                            this.userLat = pos.coords.latitude;
                            this.userLng = pos.coords.longitude;
                            
                            // Titik Pusat Sekolah
                            const schoolLat = -7.801533; 
                            const schoolLng = 110.352726; 
                            
                            // Hitung Jarak (Haversine)
                            const R = 6371e3; 
                            const p1 = schoolLat * Math.PI/180;
                            const p2 = this.userLat * Math.PI/180;
                            const dp = (this.userLat-schoolLat) * Math.PI/180;
                            const dl = (this.userLng-schoolLng) * Math.PI/180;
                            const a = Math.sin(dp/2) * Math.sin(dp/2) + Math.cos(p1) * Math.cos(p2) * Math.sin(dl/2) * Math.sin(dl/2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                            this.jarakMeter = Math.round(R * c);

                            // Batas Radius (misal 100m)
                            this.gpsValid = this.jarakMeter <= 100; 
                            this.isCheckingLocation = false;
                            this.status = { type: 'success', msg: '📍 Lokasi diperbarui' };
                        }, (err) => {
                            this.isCheckingLocation = false;
                            this.status = { type: 'error', msg: '❌ Gagal akses lokasi' };
                        });
                    }
                },
                
                startScanning() {
                    if(this.isScanning) return;
                    if(!this.userLat) { this.status = { type: 'error', msg: '❌ Aktifkan GPS Terlebih dahulu' }; return; }

                    this.isScanning = true;
                    const html5QrCode = new Html5Qrcode('reader');
                    
                    html5QrCode.start(
                        { facingMode: 'environment' }, 
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        (decodedText) => {
                            html5QrCode.stop().then(() => {
                                this.isScanning = false;
                                this.processAttendance(decodedText);
                            });
                        }
                    ).catch(() => {
                        this.isScanning = false;
                        this.status = { type: 'error', msg: '❌ Kamera gagal menyala' };
                    });
                },

                processAttendance(token) {
                    this.status = { type: 'success', msg: '⏳ Mengirim data...' };
                    fetch('/siswa/scan-proses', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            kode_qr: token, latitude: this.userLat, longitude: this.userLng    
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.status = { type: data.status === 'success' ? 'success' : 'error', msg: data.message };
                    })
                    .catch(() => {
                        this.status = { type: 'error', msg: '❌ Masalah jaringan server' };
                    });
                }
            }
        }

        // Realtime Clock
        setInterval(() => {
            const now = new Date();
            const el = document.getElementById('realtime-clock');
            if(el) el.textContent = now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0')+':'+now.getSeconds().toString().padStart(2,'0');
        }, 1000);
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>