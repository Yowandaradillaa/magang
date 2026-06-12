<x-app-layout>
    <div x-data="{ jenis: 'Sakit', fileName: '' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out max-w-2xl mx-auto space-y-6">
        
        <!-- Header Panel -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-sky-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-amber-600 uppercase bg-amber-500/10 px-3.5 py-1.5 rounded-full border border-amber-500/10">Perizinan</span>
                <h1 class="text-2xl font-black text-[#0b1e36] tracking-tight mt-3">Form Pengajuan Izin</h1>
                <p class="text-[13px] font-medium text-slate-500 mt-1.5">Silakan isi formulir di bawah ini dengan menyertakan alasan dan dokumen pendukung yang sah.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm">
            <form method="POST" action="{{ route('siswa.izin.ajukan') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Hidden Input for category -->
                <input type="hidden" name="jenis" :value="jenis">

                <!-- Category Buttons -->
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Kategori Ketidakhadiran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="jenis = 'Sakit'"
                            :class="jenis === 'Sakit' ? 'border-sky-500 bg-sky-500/10 text-sky-700 font-extrabold' : 'border-slate-200 text-slate-500 font-semibold hover:bg-slate-50'"
                            class="flex items-center justify-center gap-2.5 py-3.5 rounded-xl border-2 transition-all outline-none cursor-pointer">
                            <i data-lucide="heart-pulse" class="w-4.5 h-4.5"></i> Sakit
                        </button>
                        <button type="button" @click="jenis = 'Izin'"
                            :class="jenis === 'Izin' ? 'border-amber-500 bg-amber-500/10 text-amber-700 font-extrabold' : 'border-slate-200 text-slate-500 font-semibold hover:bg-slate-50'"
                            class="flex items-center justify-center gap-2.5 py-3.5 rounded-xl border-2 transition-all outline-none cursor-pointer">
                            <i data-lucide="file-text" class="w-4.5 h-4.5"></i> Keperluan Izin
                        </button>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Mulai Tanggal</label>
                        <input type="date" name="tanggal_mulai" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-slate-100 outline-none text-[13.5px] font-semibold text-[#0b1e36] transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Sampai Tanggal</label>
                        <input type="date" name="tanggal_selesai" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-slate-100 outline-none text-[13.5px] font-semibold text-[#0b1e36] transition-all">
                    </div>
                </div>

                <!-- Reason Text Area -->
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Alasan / Keterangan Detail</label>
                    <textarea name="alasan" rows="4" placeholder="Tuliskan alasan detail ketidakhadiran Anda..." required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-slate-100 outline-none text-[13.5px] font-medium text-[#0b1e36] placeholder-slate-300 transition-all resize-none"></textarea>
                </div>

                <!-- File Dropzone -->
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Unggah Surat Keterangan / Bukti Pendukung</label>
                    <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 p-6 text-center hover:border-sky-500 hover:bg-sky-50/10 transition-all cursor-pointer group">
                        <input type="file" name="file_surat" accept="image/*,application/pdf"
                            @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div class="flex flex-col items-center relative z-0">
                            <div class="p-3.5 bg-white rounded-xl shadow-sm border border-slate-100 mb-3 group-hover:scale-110 transition-transform duration-300 text-[#0b1e36]">
                                <i data-lucide="upload-cloud" class="w-6 h-6" stroke-width="2"></i>
                            </div>
                            <p class="text-[13px] font-bold text-[#0b1e36]" x-text="fileName ? fileName : 'Pilih surat dokter atau berkas gambar/PDF'"></p>
                            <p class="text-[11px] font-medium text-slate-400 mt-1" x-show="!fileName">Format yang diperbolehkan: PDF, JPG, PNG (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 rounded-xl font-bold transition-all shadow-md hover:shadow-lg bg-gradient-to-r from-[#0b1e36] to-[#112d52] hover:from-[#112d52] hover:to-[#1a3d6d] text-white text-[14px] mt-4 cursor-pointer">
                    Kirim Permohonan Perizinan <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

    </div>
    <script>lucide.createIcons();</script>
</x-app-layout>