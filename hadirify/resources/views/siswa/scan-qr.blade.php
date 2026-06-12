<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div x-data="scannerApp()" class="animate-in fade-in slide-in-from-bottom-8 duration-500 space-y-8">
        
        <!-- Header Panel -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-sky-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-sky-600 uppercase bg-sky-500/10 px-3.5 py-1.5 rounded-full border border-sky-500/10">Fitur Utama</span>
                <h1 class="text-2xl font-black text-[#0b1e36] tracking-tight mt-3">Scan QR Absensi</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1.5">Arahkan kamera gawai Anda tepat pada QR Code yang ditampilkan oleh Guru di depan kelas.</p>
            </div>
            
            <div class="flex items-center gap-2.5 text-[11px] font-extrabold text-slate-500 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-200/60 shrink-0">
                <span class="relative flex h-2 w-2">
                    <span :class="isScanning ? 'bg-sky-500' : 'bg-emerald-500'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                    <span :class="isScanning ? 'bg-sky-500' : 'bg-emerald-500'" class="relative inline-flex rounded-full h-2 w-2"></span>
                </span>
                <span class="tracking-wider uppercase" x-text="isScanning ? 'Kamera Aktif...' : 'Kamera Siap'"></span>
            </div>
        </div>

        <!-- Split Grid (Scanner & Mapel Hari Ini) -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Scanner Container -->
                <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm">
                    
                    <div @click="startScanning()" class="group relative flex h-[360px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-slate-200 bg-slate-950 transition-all duration-300 hover:border-sky-500">
                        <!-- QR Code Reader Element -->
                        <div id="reader" class="absolute inset-0 w-full h-full z-0 overflow-hidden object-cover" x-show="isScanning"></div>

                        <!-- Fallback Idle Layout -->
                        <div x-show="!isScanning" class="absolute flex h-full w-full items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100/30">
                            <div class="absolute flex h-full w-full items-center justify-center opacity-[0.03] transition-opacity group-hover:opacity-[0.07]">
                                <i data-lucide="camera" class="w-48 h-48 text-[#0b1e36]"></i>
                            </div>
                        </div>
                        
                        <!-- Scanner Corner Indicators -->
                        <div class="absolute z-10 left-6 top-6 h-10 w-10 border-l-4 border-t-4 border-sky-500 rounded-tl-xl"></div>
                        <div class="absolute z-10 right-6 top-6 h-10 w-10 border-r-4 border-t-4 border-sky-500 rounded-tr-xl"></div>
                        <div class="absolute z-10 bottom-6 left-6 h-10 w-10 border-b-4 border-l-4 border-sky-500 rounded-bl-xl"></div>
                        <div class="absolute z-10 bottom-6 right-6 h-10 w-10 border-b-4 border-r-4 border-sky-500 rounded-br-xl"></div>
                        
                        <!-- Center Text/Icon -->
                        <div x-show="!isScanning" class="z-10 flex flex-col items-center text-center px-4">
                            <div class="mb-4 rounded-2xl bg-white p-4 shadow-md border border-slate-100 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="qr-code" class="w-10 h-10 text-[#0b1e36]" stroke-width="2"></i>
                            </div>
                            <h3 class="text-[15px] font-black text-[#0b1e36]">Mulai Pemindaian QR</h3>
                            <p class="mt-1 text-[12px] font-semibold text-slate-400 max-w-xs">Klik area pemindai ini untuk menyalakan kamera gawai.</p>
                        </div>

                        <!-- Laser Line Scan Effect -->
                        <div x-show="isScanning" class="absolute z-20 left-0 w-full h-[3px] bg-gradient-to-r from-transparent via-sky-500 to-transparent top-0 animate-[bounce_3s_infinite] shadow-[0_0_8px_#38bdf8]"></div>
                    </div>

                    <!-- Geolocation Validation Panel -->
                    <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-2xl bg-slate-50 p-4 border border-slate-200/60 gap-4">
                        <div class="flex items-center gap-3.5">
                            <div :class="jarakMeter === null ? 'bg-slate-200 text-slate-500 border-slate-300' : (gpsValid ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200')" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border transition-colors duration-300">
                                <i data-lucide="map-pin" class="w-5 h-5" stroke-width="2.5"></i>
                            </div>
                            <div>
                                <h4 class="text-[14px] font-bold text-[#0b1e36]">Radius Presensi GPS</h4>
                                <p class="text-[12px] font-semibold text-slate-500 mt-0.5" 
                                   x-text="jarakMeter === null ? 'Belum diperiksa' : (gpsValid ? 'Lokasi Valid (Jarak: ' + jarakMeter + ' meter)' : 'Terlalu Jauh (Jarak: ' + jarakMeter + ' meter)')">
                                </p>
                            </div>
                        </div>
                        
                        <button @click="cekLokasi()" class="w-full sm:w-auto rounded-xl bg-white px-5 py-3 text-[11px] font-black text-[#0b1e36] shadow-sm border border-slate-200 hover:bg-slate-50 transition-colors whitespace-nowrap outline-none cursor-pointer">
                            <span x-text="isCheckingLocation ? 'Menghubungkan GPS...' : '📍 Perbarui Lokasi'"></span>
                        </button>
                    </div>
                </div>

                <!-- Status alert container -->
                <div x-show="status !== null" x-transition.duration.300ms
                     :class="status?.type === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700'"
                     class="rounded-2xl border p-4 text-[13px] font-bold shadow-sm flex gap-3 items-start">
                    <div class="mt-0.5"><i data-lucide="info" class="w-4 h-4" stroke-width="2.5"></i></div>
                    <span x-text="status?.msg"></span>
                </div>
            </div>

            <!-- Sidebar (Jadwal Hari Ini) -->
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden flex flex-col h-fit">
                <div class="border-b border-slate-100 p-5 flex items-center gap-2.5 bg-slate-50/50">
                    <div class="p-2 bg-sky-50 text-sky-600 rounded-xl border border-sky-100">
                        <i data-lucide="calendar" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#0b1e36]">Mata Pelajaran Hari Ini</h3>
                </div>
                
                <div class="divide-y divide-slate-100">
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/20 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#0b1e36]">Matematika</h4>
                            <p class="text-[11.5px] font-semibold text-slate-400 mt-1 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> 07:00 – 08:30 WIB
                            </p>
                        </div>
                        <span class="flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[11px] font-black text-emerald-600">
                            <i data-lucide="check" class="w-3.5 h-3.5" stroke-width="3"></i> Hadir
                        </span>
                    </div>

                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/20 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#0b1e36]">Fisika</h4>
                            <p class="text-[11.5px] font-semibold text-slate-400 mt-1 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> 10:15 – 11:45 WIB
                            </p>
                        </div>
                        <span class="flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-[11px] font-black text-amber-600 animate-pulse">
                            <i data-lucide="info" class="w-3.5 h-3.5" stroke-width="2.5"></i> Belum Absen
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerApp', () => ({
                gpsValid: false, 
                status: null,
                isScanning: false,
                isCheckingLocation: false,
                jarakMeter: null,
                
                userLat: null,
                userLng: null,
                
                cekLokasi() {
                    this.isCheckingLocation = true;
                    this.status = null;
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((pos) => {
                            this.userLat = pos.coords.latitude;
                            this.userLng = pos.coords.longitude;
                            
                            const schoolLat = -7.801533; 
                            const schoolLng = 110.352726; 
                            
                            const R = 6371e3; 
                            const p1 = schoolLat * Math.PI/180;
                            const p2 = this.userLat * Math.PI/180;
                            const dp = (this.userLat-schoolLat) * Math.PI/180;
                            const dl = (this.userLng-schoolLng) * Math.PI/180;
                            
                            const a = Math.sin(dp/2) * Math.sin(dp/2) + Math.cos(p1) * Math.cos(p2) * Math.sin(dl/2) * Math.sin(dl/2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                            const distance = Math.round(R * c);
                            
                            this.jarakMeter = distance;
                            this.gpsValid = true; 
                            
                            this.isCheckingLocation = false;
                            this.status = { type: 'success', msg: '📍 Geolokasi berhasil diperbarui!' };
                        }, (err) => {
                            this.isCheckingLocation = false;
                            this.status = { type: 'error', msg: '❌ Gagal akses lokasi. Pastikan GPS aktif dan izin peramban diberikan.' };
                        });
                    } else {
                        this.isCheckingLocation = false;
                        this.status = { type: 'error', msg: '❌ Browser Anda tidak mendukung GPS.' };
                    }
                },
                
                startScanning() {
                    if(this.isScanning) return;
                    this.isScanning = true;
                    this.status = null;

                    const html5QrCode = new Html5Qrcode('reader');
                    
                    html5QrCode.start(
                        { facingMode: 'environment' }, 
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        (decodedText) => {
                            html5QrCode.stop().then(() => {
                                this.isScanning = false;
                                
                                if(this.userLat === null || this.userLng === null) {
                                    this.status = { type: 'error', msg: '❌ Silakan perbarui lokasi GPS Anda terlebih dahulu.' };
                                    return; 
                                }
                                
                                this.status = { type: 'success', msg: '⏳ Sedang mengirim data ke server...' };

                                fetch('/siswa/scan-proses', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ 
                                        kode_qr: decodedText,      
                                        latitude: this.userLat,    
                                        longitude: this.userLng    
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.message && data.message.includes('berhasil')) {
                                        this.status = { type: 'success', msg: '✅ ' + data.message };
                                    } else {
                                        this.status = { type: 'error', msg: '❌ ' + (data.message || 'Terjadi kesalahan validasi.') };
                                    }
                                })
                                .catch((error) => {
                                    this.status = { type: 'error', msg: '❌ Server backend bermasalah / data ditolak.' };
                                });
                            });
                        },
                        (errorMessage) => { }
                    ).catch((err) => {
                        this.isScanning = false;
                        this.status = { type: 'error', msg: '❌ Kamera gagal menyala. Silakan izinkan akses kamera.' };
                    });
                }
            }));
        });
    </script>
</x-app-layout>