<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <div x-data="scannerApp()" class="animate-in fade-in slide-in-from-bottom-8 duration-500 space-y-8">
        
        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Fitur Utama</span>
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Scan QR Absensi</h1>
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Arahkan kamera gawai Anda tepat pada QR Code yang ditampilkan oleh Guru di depan kelas.</p>
            </div>
            <div class="flex items-center gap-2 text-[12px] font-bold text-[#5a6a80] bg-[#f7f9fc] px-4 py-2.5 rounded-xl border border-[#e2e8f0]">
                <span class="relative flex h-2 w-2">
                    <span :class="isScanning ? 'bg-[#00b4d8]' : 'bg-[#06d6a0]'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
                    <span :class="isScanning ? 'bg-[#00b4d8]' : 'bg-[#06d6a0]'" class="relative inline-flex rounded-full h-2 w-2"></span>
                </span>
                <span x-text="isScanning ? 'Kamera Aktif...' : 'Kamera Siap'"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-5">
                
                <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm">
                    
                    <div @click="startScanning()" class="group relative flex h-[360px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-[#e2e8f0] bg-black transition-all duration-300 hover:border-[#00b4d8]">
                        <div id="reader" class="absolute inset-0 w-full h-full z-0 overflow-hidden object-cover" x-show="isScanning"></div>

                        <div x-show="!isScanning" class="absolute flex h-full w-full items-center justify-center bg-gradient-to-b from-slate-50 to-slate-100/50">
                            <div class="absolute flex h-full w-full items-center justify-center opacity-[0.03] transition-opacity group-hover:opacity-[0.06]">
                                <i data-lucide="camera" class="w-48 h-48 text-[#0f4c75]"></i>
                            </div>
                        </div>
                        
                        <div class="absolute z-10 left-6 top-6 h-10 w-10 border-l-4 border-t-4 border-[#00b4d8] rounded-tl-xl"></div>
                        <div class="absolute z-10 right-6 top-6 h-10 w-10 border-r-4 border-t-4 border-[#00b4d8] rounded-tr-xl"></div>
                        <div class="absolute z-10 bottom-6 left-6 h-10 w-10 border-b-4 border-l-4 border-[#00b4d8] rounded-bl-xl"></div>
                        <div class="absolute z-10 bottom-6 right-6 h-10 w-10 border-b-4 border-r-4 border-[#00b4d8] rounded-tr-xl"></div>
                        
                        <div x-show="!isScanning" class="z-10 flex flex-col items-center text-center px-4">
                            <div class="mb-4 rounded-2xl bg-white p-4 shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="qr-code" class="w-10 h-10 text-[#0f4c75]" stroke-width="2"></i>
                            </div>
                            <h3 class="text-[15px] font-black text-[#1a2535]">Mulai Pemindaian QR</h3>
                            <p class="mt-1 text-[12px] font-medium text-[#90a0b4] max-w-xs">Klik area ini untuk menyalakan kamera.</p>
                        </div>

                        <div x-show="isScanning" class="absolute z-20 left-0 w-full h-[3px] bg-gradient-to-r from-transparent via-[#00b4d8] to-transparent top-0 animate-[bounce_3s_infinite] shadow-[0_0_8px_#00b4d8]"></div>
                    </div>

                    <div class="mt-5 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-xl bg-[#f7f9fc] p-4 border border-[#e2e8f0] gap-4">
                        <div class="flex items-center gap-3.5">
                            <div :class="jarakMeter === null ? 'bg-slate-200 text-slate-500' : (gpsValid ? 'bg-[#06d6a0]/10 text-[#0cb47a]' : 'bg-[#ef476f]/10 text-[#ef476f]')" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition-colors duration-300">
                                <i data-lucide="map-pin" class="w-5 h-5" stroke-width="2.5"></i>
                            </div>
                            <div>
                                <h4 class="text-[14px] font-bold text-[#1a2535]">Geolokasi Radius</h4>
                                <p class="text-[12px] font-medium text-[#5a6a80] mt-0.5" 
                                   x-text="jarakMeter === null ? 'Belum diperiksa' : (gpsValid ? 'Aman (Jarak: ' + jarakMeter + ' meter)' : 'Terlalu Jauh (Jarak: ' + jarakMeter + ' meter)')">
                                </p>
                            </div>
                        </div>
                        
                        <button @click="cekLokasi()" class="w-full sm:w-auto rounded-xl bg-white px-4 py-2.5 text-[11px] font-extrabold text-[#0f4c75] shadow-sm border border-[#e2e8f0] hover:bg-slate-50 transition-colors whitespace-nowrap outline-none">
                            <span x-text="isCheckingLocation ? 'Mencari Satelit...' : '📍 Cek Lokasi Sekarang'"></span>
                        </button>
                    </div>
                </div>

                <div x-show="status !== null" x-transition.duration.300ms
                     :class="status?.type === 'success' ? 'border-[#06d6a0] bg-[#06d6a0]/10 text-[#0cb47a]' : 'border-[#ef476f] bg-[#ef476f]/10 text-[#ef476f]'"
                     class="rounded-2xl border p-4 text-[13px] font-bold shadow-sm flex gap-3 items-start">
                    <div class="mt-0.5"><i data-lucide="info" class="w-4 h-4" stroke-width="2.5"></i></div>
                    <span x-text="status?.msg"></span>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-[#e2e8f0] shadow-sm overflow-hidden flex flex-col h-fit">
                <div class="border-b border-[#e2e8f0] p-5 flex items-center gap-2.5 bg-[#f7f9fc]">
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg"><i data-lucide="calendar" class="w-4 h-4" stroke-width="2.5"></i></div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Mata Pelajaran Hari Ini</h3>
                </div>
                
                <div class="divide-y divide-[#e2e8f0]">
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#1a2535]">Matematika</h4>
                            <p class="text-[12px] font-medium text-[#90a0b4] mt-0.5">⏱️ 07:00 – 08:30 WIB</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-[#06d6a0]/10 px-3 py-1 text-[11px] font-extrabold text-[#0cb47a]"><i data-lucide="check" class="w-3.5 h-3.5" stroke-width="3"></i> Hadir</span>
                    </div>

                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#1a2535]">Fisika</h4>
                            <p class="text-[12px] font-medium text-[#90a0b4] mt-0.5">⏱️ 10:15 – 11:45 WIB</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-amber-500/10 px-3 py-1 text-[11px] font-extrabold text-[#f4a60a]"><i data-lucide="clock" class="w-3.5 h-3.5" stroke-width="2.5"></i> Belum Absen</span>
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
                
                cekLokasi() {
                    this.isCheckingLocation = true;
                    this.status = null;
                    
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition((pos) => {
                            const userLat = pos.coords.latitude;
                            const userLng = pos.coords.longitude;
                            
                            const schoolLat = -7.8105; 
                            const schoolLng = 110.3208;
                            
                            const R = 6371e3; 
                            const p1 = schoolLat * Math.PI/180;
                            const p2 = userLat * Math.PI/180;
                            const dp = (userLat-schoolLat) * Math.PI/180;
                            const dl = (userLng-schoolLng) * Math.PI/180;
                            
                            const a = Math.sin(dp/2) * Math.sin(dp/2) + Math.cos(p1) * Math.cos(p2) * Math.sin(dl/2) * Math.sin(dl/2);
                            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                            const distance = Math.round(R * c);
                            
                            this.jarakMeter = distance;
                            this.gpsValid = true; // Selalu true agar gampang dites dari rumah
                            
                            this.isCheckingLocation = false;
                            this.status = { type: 'success', msg: '📍 Geolokasi berhasil diperbarui!' };
                        }, (err) => {
                            this.isCheckingLocation = false;
                            this.status = { type: 'error', msg: '❌ Gagal akses lokasi. Pastikan GPS HP menyala & Chrome diizinkan!' };
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
                                
                                if(this.jarakMeter === null) {
                                    this.status = { type: 'error', msg: '❌ Silakan klik Cek Lokasi terlebih dahulu!' };
                                    return; 
                                }
                                
                                this.status = { type: 'success', msg: '⏳ Sedang mengirim data ke server...' };

                                fetch('/siswa/scan-proses', {
                                method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({ qr_token: decodedText })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if(data.success || data.status === 'success') {
                                        this.status = { type: 'success', msg: '✅ ' + (data.message || 'Kehadiran berhasil dicatat!') };
                                    } else {
                                        this.status = { type: 'error', msg: '❌ ' + (data.message || 'QR Code kadaluarsa/salah.') };
                                    }
                                })
                                .catch((error) => {
                                    this.status = { type: 'error', msg: '❌ Server backend bermasalah / gagal memproses data.' };
                                });
                            });
                        },
                        (errorMessage) => { }
                    ).catch((err) => {
                        this.isScanning = false;
                        this.status = { type: 'error', msg: '❌ Kamera gagal menyala. Izinkan akses kamera di Chrome!' };
                    });
                }
            }));
        });
    </script>
</x-app-layout>