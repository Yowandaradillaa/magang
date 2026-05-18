<x-app-layout>
    <div x-data="{ 
            gpsValid: true, 
            status: null,
            handleScan() {
                if(!this.gpsValid) {
                    this.status = { type: 'error', msg: '❌ Absensi Gagal: Anda berada di luar radius lokasi sekolah (>200m)!' };
                } else {
                    this.status = { type: 'success', msg: '✅ Absensi Berhasil: Kehadiran Anda untuk mata pelajaran Fisika telah tercatat tepat waktu!' };
                }
            }
         }" 
         class="animate-in fade-in slide-in-from-bottom-8 duration-500 space-y-8">
        
        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <span class="text-[11px] font-extrabold tracking-widest text-[#00b4d8] uppercase bg-[#00b4d8]/10 px-3 py-1 rounded-full">Fitur Utama</span>
                <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Scan QR Absensi</h1>
                <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Arahkan kamera gawai Anda tepat pada QR Code yang ditampilkan oleh Guru di depan kelas.</p>
            </div>
            <div class="flex items-center gap-2 text-[12px] font-bold text-[#5a6a80] bg-[#f7f9fc] px-4 py-2.5 rounded-xl border border-[#e2e8f0]">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#06d6a0] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#06d6a0]"></span>
                </span>
                <span>Kamera Siap</span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            
            <div class="lg:col-span-2 space-y-5">
                
                <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm">
                    
                    <div @click="handleScan()" class="group relative flex h-[360px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-2xl border-2 border-dashed border-[#e2e8f0] bg-gradient-to-b from-slate-50 to-slate-100/50 transition-all duration-300 hover:border-[#00b4d8] hover:bg-sky-50/40">
                        
                        <div class="absolute flex h-full w-full items-center justify-center opacity-[0.03] transition-opacity group-hover:opacity-[0.06]">
                            <i data-lucide="camera" class="w-48 h-48 text-[#0f4c75]"></i>
                        </div>
                        
                        <div class="absolute left-6 top-6 h-10 w-10 border-l-4 border-t-4 border-[#00b4d8] rounded-tl-xl transition-all group-hover:scale-105"></div>
                        <div class="absolute right-6 top-6 h-10 w-10 border-r-4 border-t-4 border-[#00b4d8] rounded-tr-xl transition-all group-hover:scale-105"></div>
                        <div class="absolute bottom-6 left-6 h-10 w-10 border-b-4 border-l-4 border-[#00b4d8] rounded-bl-xl transition-all group-hover:scale-105"></div>
                        <div class="absolute bottom-6 right-6 h-10 w-10 border-b-4 border-r-4 border-[#00b4d8] rounded-tr-xl transition-all group-hover:scale-105"></div>
                        
                        <div class="z-10 flex flex-col items-center text-center px-4">
                            <div class="mb-4 rounded-2xl bg-white p-4 shadow-md shadow-slate-200/80 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="qr-code" class="w-10 h-10 text-[#0f4c75]" stroke-width="2"></i>
                            </div>
                            <h3 class="text-[15px] font-black text-[#1a2535]">Posisikan Kode QR di Dalam Kotak</h3>
                            <p class="mt-1 text-[12px] font-medium text-[#90a0b4] max-w-xs">Sistem akan memproses absensi secara otomatis. <span class="text-[#00b4d8] font-bold">Klik area ini untuk simulasi scan</span>.</p>
                        </div>

                        <div class="absolute left-0 w-full h-[3px] bg-gradient-to-r from-transparent via-[#00b4d8] to-transparent top-0 animate-[bounce_3s_infinite] opacity-60"></div>
                    </div>

                    <div class="mt-5 flex flex-col sm:flex-row items-start sm:items-center justify-between rounded-xl bg-[#f7f9fc] p-4 border border-[#e2e8f0] gap-4">
                        <div class="flex items-center gap-3.5">
                            <div :class="gpsValid ? 'bg-[#06d6a0]/10 text-[#0cb47a]' : 'bg-[#ef476f]/10 text-[#ef476f]'" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition-colors duration-300">
                                <i data-lucide="map-pin" class="w-5 h-5" stroke-width="2.5"></i>
                            </div>
                            <div>
                                <h4 class="text-[14px] font-bold text-[#1a2535]">Geolokasi Radius Sekolah</h4>
                                <p class="text-[12px] font-medium text-[#5a6a80] mt-0.5" x-text="gpsValid ? 'Sesuai (Jarak Kamu: 12 meter dari koordinat Sekolah)' : 'Di Luar Jangkauan (Jarak Kamu: 340 meter dari Sekolah)'"></p>
                            </div>
                        </div>
                        
                        <button @click="gpsValid = !gpsValid" class="w-full sm:w-auto rounded-xl bg-white px-4 py-2.5 text-[11px] font-extrabold text-[#0f4c75] shadow-sm border border-[#e2e8f0] hover:bg-slate-50 hover:text-[#1b6ca8] transition-colors whitespace-nowrap">
                            📡 Simulasikan Pindah Lokasi
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
                    <div class="p-2 bg-[#0f4c75]/10 text-[#0f4c75] rounded-lg">
                        <i data-lucide="calendar" class="w-4 h-4" stroke-width="2.5"></i>
                    </div>
                    <h3 class="text-[15px] font-extrabold text-[#1a2535]">Mata Pelajaran Hari Ini</h3>
                </div>
                
                <div class="divide-y divide-[#e2e8f0]">
                    
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#1a2535]">Matematika</h4>
                            <p class="text-[12px] font-medium text-[#90a0b4] mt-0.5">⏱️ 07:00 – 08:30 WIB</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-[#06d6a0]/10 px-3 py-1 text-[11px] font-extrabold text-[#0cb47a]">
                            <i data-lucide="check" class="w-3.5 h-3.5" stroke-width="3"></i> Hadir
                        </span>
                    </div>

                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#1a2535]">Fisika</h4>
                            <p class="text-[12px] font-medium text-[#90a0b4] mt-0.5">⏱️ 10:15 – 11:45 WIB</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-amber-500/10 px-3 py-1 text-[11px] font-extrabold text-[#f4a60a]">
                            <i data-lucide="clock" class="w-3.5 h-3.5" stroke-width="2.5"></i> Belum Absen
                        </span>
                    </div>

                    <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div>
                            <h4 class="text-[14px] font-bold text-[#1a2535]">Bahasa Indonesia</h4>
                            <p class="text-[12px] font-medium text-[#90a0b4] mt-0.5">⏱️ 13:00 – 14:30 WIB</p>
                        </div>
                        <span class="flex items-center gap-1.5 rounded-full bg-slate-100 text-slate-400 px-3 py-1 text-[11px] font-extrabold">
                            <i data-lucide="minus-circle" class="w-3.5 h-3.5" stroke-width="2.5"></i> Nanti
                        </span>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>