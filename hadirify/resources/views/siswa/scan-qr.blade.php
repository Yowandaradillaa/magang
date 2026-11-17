<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <!-- Container Utama: Fixed Header & Scrollable Content -->
    <div x-data="scannerApp()" class="animate-in fade-in duration-700 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- ================= SECTION 1: POPUP MODAL BERHASIL (KOTAK BERWIBAWA & MODERN) ================= -->
        <template x-teleport="body">
            <div x-show="showSuccessModal" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-[#0b1e36]/60 backdrop-blur-sm px-4 transition-all">
                <div @click.away="closeSuccessModal(false)"
                     x-show="showSuccessModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-xl p-6 sm:p-8 max-w-md w-full text-center shadow-2xl border border-slate-100 relative overflow-hidden">
                    
                    <!-- Top Color Accent Bar (Emerald) -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-teal-400"></div>

                    <!-- Tombol Silang Pojok Kanan Atas -->
                    <button @click="closeSuccessModal(false)" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>

                    <!-- Icon Sukses Besar Elegan -->
                    <div class="mx-auto mb-4 flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-xs">
                        <i data-lucide="check-circle-2" class="w-9 h-9 stroke-[2.5px]"></i>
                    </div>
                    
                    <h3 class="text-xl font-extrabold text-[#0b1e36] tracking-tight">Presensi Berhasil Dicatat!</h3>
                    <p class="text-xs font-medium text-slate-500 mt-1" x-text="successData?.pesan || 'Kehadiran Anda telah diverifikasi oleh sistem.'"></p>
                    
                    <!-- Kartu Rincian Presensi (Clean Kotak Berwibawa) -->
                    <div class="mt-5 p-4 bg-slate-50 border border-slate-200/80 rounded-lg text-left space-y-2.5">
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Mata Pelajaran</span>
                            <span class="text-xs font-black text-slate-800" x-text="successData?.mapel"></span>
                        </div>
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Guru Pengampu</span>
                            <span class="text-xs font-bold text-slate-700" x-text="successData?.guru"></span>
                        </div>
                        <div class="flex items-center justify-between pb-2.5 border-b border-slate-200/60">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Waktu Presensi</span>
                            <span class="text-xs font-mono font-bold text-slate-700" x-text="successData?.waktu"></span>
                        </div>
                        <div class="flex items-center justify-between pt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Kehadiran</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span x-text="successData?.status || 'Hadir Tepat Waktu'"></span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-2.5">
                        <a href="{{ route('siswa.rekap') }}" 
                           class="flex-1 py-2.5 px-3 rounded-lg font-bold text-xs text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 transition-all text-center">
                            Lihat Rekap
                        </a>
                        <button @click="closeSuccessModal(true)"
                                class="flex-1 py-2.5 px-3 rounded-lg font-black text-xs uppercase tracking-wider text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-600/20 active:scale-95 transition-all text-center">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= SECTION 2: POPUP INPUT TOKEN MANUAL (CADANGAN) ================= -->
        <template x-teleport="body">
            <div x-show="showManualModal" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-[#0b1e36]/60 backdrop-blur-sm px-4 transition-all">
                <div @click.away="showManualModal = false"
                     x-show="showManualModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     class="bg-white rounded-xl p-6 max-w-sm w-full text-center shadow-2xl border border-slate-100 relative">
                    
                    <button @click="showManualModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>

                    <div class="mx-auto mb-3 flex items-center justify-center w-12 h-12 rounded-full bg-sky-50 text-sky-600 border border-sky-100">
                        <i data-lucide="key-round" class="w-6 h-6"></i>
                    </div>

                    <h3 class="text-base font-extrabold text-[#0b1e36] tracking-tight">Input Token Presensi</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Salin atau ketik kode token sesi yang tertera pada layar guru.</p>

                    <div class="mt-4">
                        <input type="text" 
                               x-model="manualToken" 
                               placeholder="Tempel kode token..." 
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-bold text-slate-800 placeholder:text-slate-400 outline-none focus:border-sky-500 focus:bg-white transition-all text-center">
                    </div>

                    <div class="mt-5 flex gap-2">
                        <button @click="showManualModal = false" 
                                class="flex-1 py-2 px-3 rounded-lg text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                            Batal
                        </button>
                        <button @click="submitManualToken()" 
                                class="flex-1 py-2 px-3 rounded-lg text-xs font-black uppercase tracking-wider text-white bg-[#0b1e36] hover:bg-slate-800 shadow-sm transition-all active:scale-95">
                            Kirim Absen
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= SECTION 3: HEADER (FIXED - KOTAK ELEGAN) ================= -->
        <div class="flex-none bg-white border border-slate-200/70 rounded-lg shadow-2xs">
            <div class="p-4 sm:px-6 sm:py-3.5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0b1e36] flex items-center justify-center rounded-md shadow-sm">
                        <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-base font-extrabold text-[#0b1e36] tracking-tight leading-none">Pemindai Absensi</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic" x-text="isScanning ? 'Mengarahkan kamera ke QR guru...' : 'Arahkan kamera ke QR depan kelas'"></p>
                    </div>
                </div>

                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-md border border-slate-200/70">
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

        <!-- ================= SECTION 4: CONTENT (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10 space-y-4">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                
                <!-- KOLOM KIRI: SCANNER (Clean Minimalist) -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="bg-white p-5 rounded-lg border border-slate-200/70 shadow-xs">
                        
                        <!-- Area Kamera / Scanner -->
                        <div class="group relative flex h-[380px] flex-col items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-slate-200 bg-slate-900 transition-all">
                            <!-- Reader Element -->
                            <div id="reader" class="absolute inset-0 w-full h-full z-0 overflow-hidden" x-show="isScanning"></div>

                            <!-- Corners Scanner -->
                            <div class="absolute z-10 left-5 top-5 h-8 w-8 border-l-4 border-t-4 border-sky-500 rounded-tl-sm pointer-events-none"></div>
                            <div class="absolute z-10 right-5 top-5 h-8 w-8 border-r-4 border-t-4 border-sky-500 rounded-tr-sm pointer-events-none"></div>
                            <div class="absolute z-10 bottom-5 left-5 h-8 w-8 border-b-4 border-l-4 border-sky-500 rounded-bl-sm pointer-events-none"></div>
                            <div class="absolute z-10 bottom-5 right-5 h-8 w-8 border-b-4 border-r-4 border-sky-500 rounded-br-sm pointer-events-none"></div>

                            <!-- Idle State (Klik Untuk Memindai) -->
                            <div x-show="!isScanning" @click="startScanning()" class="z-10 text-center cursor-pointer p-6">
                                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center mx-auto mb-4 shadow-xl group-hover:scale-105 transition-transform">
                                    <i data-lucide="maximize" class="w-8 h-8 text-sky-600"></i>
                                </div>
                                <h3 class="text-sm font-black text-white uppercase tracking-widest">Ketuk Untuk Memindai</h3>
                                <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tighter">Aktifkan kamera live gawai Anda</p>
                            </div>

                            <!-- Tombol Hentikan Kamera Saat Scanning -->
                            <div x-show="isScanning" class="absolute z-30 bottom-4">
                                <button @click="stopScanning()" class="px-4 py-1.5 bg-rose-600/90 hover:bg-rose-700 text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-lg backdrop-blur-sm transition-all active:scale-95 flex items-center gap-1.5">
                                    <i data-lucide="camera-off" class="w-3.5 h-3.5"></i> Tutup Kamera
                                </button>
                            </div>

                            <!-- Laser Scan Line -->
                            <div x-show="isScanning" class="absolute z-20 left-0 w-full h-[2px] bg-sky-400 top-0 animate-[bounce_3s_infinite] shadow-[0_0_15px_#38bdf8] pointer-events-none"></div>
                        </div>

                        <!-- Opsi Cadangan Pemindaian (Upload Gambar & Input Manual) -->
                        <div class="mt-3.5 flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100 text-xs">
                            <span class="text-[11px] font-bold text-slate-400">Alternatif Presensi:</span>
                            
                            <div class="flex items-center gap-2">
                                <!-- Hidden File Input -->
                                <input type="file" 
                                       id="qr-file-input" 
                                       accept="image/*" 
                                       @change="scanUploadedFile($event)" 
                                       class="hidden">

                                <button type="button" 
                                        @click="document.getElementById('qr-file-input').click()" 
                                        class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-md text-[10px] font-bold text-slate-600 flex items-center gap-1.5 transition-all cursor-pointer">
                                    <i data-lucide="image" class="w-3.5 h-3.5 text-sky-600"></i>
                                    Unggah Foto QR
                                </button>

                                <button type="button" 
                                        @click="showManualModal = true" 
                                        class="px-2.5 py-1.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-md text-[10px] font-bold text-slate-600 flex items-center gap-1.5 transition-all cursor-pointer">
                                    <i data-lucide="keyboard" class="w-3.5 h-3.5 text-amber-600"></i>
                                    Input Token Manual
                                </button>
                            </div>
                        </div>

                        <!-- GPS Info Bar -->
                        <div class="mt-3 flex items-center justify-between p-3 bg-slate-50 border border-slate-200/80 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div :class="jarakMeter === null ? 'bg-slate-200 text-slate-400' : (gpsValid ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600')" class="w-9 h-9 rounded-md flex items-center justify-center transition-colors">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Validasi Lokasi</p>
                                    <p class="text-[11px] font-bold text-slate-700 mt-1" x-text="jarakMeter === null ? 'GPS belum terhubung' : (gpsValid ? 'Area Sekolah ('+jarakMeter+'m)' : 'Di luar jangkauan ('+jarakMeter+'m)')"></p>
                                </div>
                            </div>
                            <button @click="cekLokasi()" class="px-3 py-1.5 bg-white border border-slate-200 text-[9px] font-black uppercase text-slate-600 rounded-md hover:bg-slate-100 active:scale-95 transition-all cursor-pointer">
                                Perbarui GPS
                            </button>
                        </div>
                    </div>

                    <!-- Status Notification (Inline untuk error/peringatan) -->
                    <div x-show="status !== null" x-transition 
                         :class="status?.type === 'success' ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : (status?.type === 'loading' ? 'bg-sky-50 border-sky-100 text-sky-700' : 'bg-rose-50 border-rose-100 text-rose-700')"
                         class="p-4 rounded-lg border flex gap-3 items-center animate-in zoom-in duration-300">
                        <i :data-lucide="status?.type === 'loading' ? 'loader' : (status?.type === 'success' ? 'check-circle' : 'alert-circle')" class="w-4 h-4 shrink-0" :class="status?.type === 'loading' ? 'animate-spin' : ''"></i>
                        <span class="text-xs font-bold uppercase tracking-tight" x-text="status?.msg"></span>
                    </div>
                </div>

                <!-- KOLOM KANAN: JADWAL (Clean Minimalist) -->
                <div class="lg:col-span-4 bg-white rounded-lg border border-slate-200/70 shadow-xs flex flex-col">
                    
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar-check" class="w-4 h-4 text-slate-400"></i>
                            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Sesi Hari Ini</h3>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ now()->locale('id')->dayName }}</span>
                    </div>

                    <div class="divide-y divide-slate-50 overflow-y-auto">
                        <!-- DATA DINAMIS JADWAL -->
                        @forelse($jadwalHariIni ?? [] as $jadwal)
                        <div class="p-5 hover:bg-slate-50/50 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-tight leading-none">{{ $jadwal->mapel->nama_mapel }}</h4>
                                        @if(!empty($jadwal->is_active_qr))
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[8px] font-black bg-sky-50 text-sky-600 border border-sky-200 uppercase tracking-wider">
                                                Live
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] font-medium text-slate-400">{{ $jadwal->kelas->nama_kelas ?? 'Kelas' }}</p>
                                </div>

                                <!-- Badge Status Berdasarkan Tabel Absensi -->
                                @php
                                    $sudahAbsen = $jadwal->absensis->where('siswa_id', Auth::id())->where('tanggal', now()->toDateString())->first();
                                    $labelStatus = $sudahAbsen ? match ($sudahAbsen->status) {
                                        'H' => 'Hadir',
                                        'I' => 'Izin',
                                        'S' => 'Sakit',
                                        'A' => 'Alfa',
                                        default => 'Tercatat',
                                    } : null;
                                @endphp
                                
                                @if($sudahAbsen)
                                    <span class="text-[8px] font-black px-2 py-0.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded uppercase flex items-center gap-1">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i> {{ $labelStatus }}
                                    </span>
                                @elseif(!empty($jadwal->is_active_qr))
                                    <span class="text-[8px] font-black px-2 py-0.5 bg-sky-50 text-sky-600 border border-sky-200 rounded uppercase animate-pulse">Sesi Buka</span>
                                @else
                                    <span class="text-[8px] font-black px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-100 rounded uppercase">Belum Scan</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-slate-400 mt-2">
                                <span class="text-[10px] font-bold flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
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
                showSuccessModal: false,
                showManualModal: false,
                manualToken: '',
                successData: null,
                html5QrCode: null,
                
                closeSuccessModal(reload = true) {
                    this.showSuccessModal = false;
                    if (reload) {
                        window.location.reload();
                    }
                },

                cekLokasi() {
                    this.isCheckingLocation = true;
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((pos) => {
                            this.userLat = pos.coords.latitude;
                            this.userLng = pos.coords.longitude;
                            
                            // Titik Pusat Sekolah
                            const schoolLat = @json(config('attendance.school.latitude'));
                            const schoolLng = @json(config('attendance.school.longitude'));
                            
                            // Hitung Jarak (Haversine)
                            const R = 6371e3; 
                            const p1 = schoolLat * Math.PI/180;
                            const p2 = this.userLat * Math.PI/180;
                            const dp = (this.userLat-schoolLat) * Math.PI/180;
                            const dl = (this.userLng-schoolLng) * Math.PI/180;
                            const a = Math.sin(dp/2) * Math.sin(dp/2) + Math.cos(p1) * Math.cos(p2) * Math.sin(dl/2) * Math.sin(dl/2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                            const jarakMeter = R * c;
                            this.jarakMeter = Math.round(jarakMeter);

                            this.gpsValid = jarakMeter <= @json(config('attendance.school.radius_meters'));
                            this.isCheckingLocation = false;
                            this.status = { type: 'success', msg: '📍 Lokasi GPS diperbarui (' + this.jarakMeter + 'm dari sekolah)' };
                        }, (err) => {
                            this.isCheckingLocation = false;
                            this.status = { type: 'error', msg: '❌ Gagal mendapatkan izin akses lokasi GPS' };
                        });
                    } else {
                        this.isCheckingLocation = false;
                        this.status = { type: 'error', msg: '❌ Perangkat tidak mendukung Geolocation GPS' };
                    }
                },
                
                startScanning() {
                    if(this.isScanning) return;
                    if(this.userLat === null || this.userLng === null) {
                        this.status = { type: 'error', msg: '❌ Harap perbarui/aktifkan GPS terlebih dahulu' };
                        return;
                    }
                    if(!this.gpsValid) {
                        this.status = {
                            type: 'error',
                            msg: '⚠️ Anda harus berada maksimal ' + @json(config('attendance.school.radius_meters')) + ' meter dari sekolah'
                        };
                        return;
                    }

                    this.isScanning = true;
                    this.status = null;

                    if (!this.html5QrCode) {
                        this.html5QrCode = new Html5Qrcode('reader', { 
                            experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                            verbose: false 
                        });
                    }

                    const config = {
                        fps: 20,
                        qrbox: (viewfinderWidth, viewfinderHeight) => {
                            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                            const size = Math.floor(minEdge * 0.75);
                            return { width: size, height: size };
                        },
                        aspectRatio: 1.0
                    };

                    const onSuccess = (decodedText) => {
                        this.stopScanning();
                        this.processAttendance(decodedText);
                    };

                    // Coba kamera belakang (environment) terlebih dahulu, jika gagal fallback ke default kamera
                    this.html5QrCode.start({ facingMode: 'environment' }, config, onSuccess)
                        .catch(() => {
                            return this.html5QrCode.start({ facingMode: 'user' }, config, onSuccess);
                        })
                        .catch((err) => {
                            this.isScanning = false;
                            this.status = { type: 'error', msg: '❌ Kamera tidak dapat diakses (' + (err.message || 'Izin kamera ditolak') + ')' };
                        });
                },

                stopScanning() {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        }).catch(() => {
                            this.isScanning = false;
                        });
                    } else {
                        this.isScanning = false;
                    }
                },

                scanUploadedFile(event) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (this.userLat === null || this.userLng === null) {
                        this.status = { type: 'error', msg: '❌ Harap perbarui/aktifkan GPS terlebih dahulu' };
                        event.target.value = '';
                        return;
                    }
                    if (!this.gpsValid) {
                        this.status = {
                            type: 'error',
                            msg: '⚠️ Anda harus berada maksimal ' + @json(config('attendance.school.radius_meters')) + ' meter dari sekolah'
                        };
                        event.target.value = '';
                        return;
                    }

                    this.status = { type: 'loading', msg: '🔍 Membaca kode QR dari gambar...' };
                    
                    if (!this.html5QrCode) {
                        this.html5QrCode = new Html5Qrcode('reader', { 
                            experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                            verbose: false 
                        });
                    }

                    this.html5QrCode.scanFile(file, true)
                        .then(decodedText => {
                            this.processAttendance(decodedText);
                            event.target.value = '';
                        })
                        .catch(() => {
                            this.status = { type: 'error', msg: '❌ Kode QR tidak terbaca pada gambar. Pastikan gambar jelas dan tidak buram.' };
                            event.target.value = '';
                        });
                },

                submitManualToken() {
                    if (!this.manualToken || this.manualToken.trim().length === 0) {
                        this.status = { type: 'error', msg: '❌ Masukkan kode token presensi terlebih dahulu' };
                        return;
                    }
                    if (this.userLat === null || this.userLng === null) {
                        this.status = { type: 'error', msg: '❌ Harap perbarui/aktifkan GPS terlebih dahulu' };
                        return;
                    }
                    if (!this.gpsValid) {
                        this.status = {
                            type: 'error',
                            msg: '⚠️ Anda harus berada maksimal ' + @json(config('attendance.school.radius_meters')) + ' meter dari sekolah'
                        };
                        return;
                    }

                    const token = this.manualToken.trim();
                    this.showManualModal = false;
                    this.manualToken = '';
                    this.processAttendance(token);
                },

                processAttendance(token) {
                    this.status = { type: 'loading', msg: '⏳ Memverifikasi data presensi...' };
                    fetch('/siswa/scan-proses', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 
                            kode_qr: token, latitude: this.userLat, longitude: this.userLng    
                        })
                    })
                    .then(res => res.json().then(data => ({ ok: res.ok, status: res.status, data })))
                    .then(response => {
                        if (response.ok && response.data.status === 'success') {
                            this.status = null;
                            const resData = response.data.data || {};
                            this.successData = {
                                mapel: resData.mapel || 'Mata Pelajaran',
                                guru: resData.guru || 'Guru Pengampu',
                                kelas: resData.kelas || '',
                                waktu: resData.waktu || (new Date().toLocaleTimeString('id-ID') + ' WIB'),
                                tanggal: resData.tanggal || '',
                                status: resData.status || 'Hadir Tepat Waktu',
                                pesan: response.data.message || 'Presensi berhasil dicatat! Selamat belajar.'
                            };
                            this.showSuccessModal = true;
                            this.$nextTick(() => {
                                if (window.lucide) { lucide.createIcons(); }
                            });
                        } else {
                            const errorMsg = response.data?.message || 'Gagal memproses presensi.';
                            this.status = { type: 'error', msg: '❌ ' + errorMsg };
                        }
                    })
                    .catch(() => {
                        this.status = { type: 'error', msg: '❌ Terjadi gangguan jaringan server.' };
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
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        #reader__scan_region {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        #reader img, #reader__dashboard {
            display: none !important;
        }
    </style>
</x-app-layout>
