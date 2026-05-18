<x-app-layout>
    <div x-data="{ jenis: 'Sakit', fileName: '' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out max-w-2xl mx-auto space-y-6">
        
        <div class="bg-white p-6 rounded-[24px] border border-[#e2e8f0] shadow-sm">
            <span class="text-[11px] font-extrabold tracking-widest text-[#f4a60a] uppercase bg-[#ffd166]/20 px-3 py-1 rounded-full">Perizinan</span>
            <h1 class="text-2xl font-black text-[#1a2535] tracking-tight mt-2">Form Pengajuan Izin</h1>
            <p class="text-[13px] font-medium text-[#5a6a80] mt-0.5">Silakan isi formulir di bawah ini dengan menyertakan alasan dan dokumen pendukung yang sah.</p>
        </div>

        <div class="bg-white p-8 rounded-[24px] border border-[#e2e8f0] shadow-sm">
            <form method="POST" action="/izin/ajukan" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[11px] font-black text-[#90a0b4] uppercase tracking-wider mb-2">Kategori Ketidakhadiran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="jenis = 'Sakit'"
                            :class="jenis === 'Sakit' ? 'border-[#00b4d8] bg-sky-50/50 text-[#00b4d8]' : 'border-[#e2e8f0] text-[#5a6a80]'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 font-bold text-[13.5px] transition-all outline-none">
                            <i data-lucide="heart-pulse" class="w-4 h-4"></i> Sakit
                        </button>
                        <button type="button" @click="jenis = 'Izin'"
                            :class="jenis === 'Izin' ? 'border-[#00b4d8] bg-sky-50/50 text-[#00b4d8]' : 'border-[#e2e8f0] text-[#5a6a80]'"
                            class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 font-bold text-[13.5px] transition-all outline-none">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Keperluan Izin
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-[#90a0b4] uppercase tracking-wider mb-2">Tanggal Berhalangan</label>
                    <input type="date" name="tanggal" required
                        class="w-full px-4 py-3 rounded-xl border-2 border-[#e2e8f0] focus:border-[#00b4d8] outline-none text-[13.5px] font-medium transition-colors">
                </div>

                <div>
                    <label class="block text-[11px] font-black text-[#90a0b4] uppercase tracking-wider mb-2">Alasan / Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Tuliskan alasan detail berkendala..." required
                        class="w-full px-4 py-3 rounded-xl border-2 border-[#e2e8f0] focus:border-[#00b4d8] outline-none text-[13.5px] font-medium transition-colors resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-[#90a0b4] uppercase tracking-wider mb-2">Unggah Surat Keterangan (PDF / Gambar)</label>
                    <div class="relative flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#e2e8f0] bg-slate-50/50 p-6 text-center hover:border-[#00b4d8] transition-colors cursor-pointer group">
                        <input type="file" name="surat" accept="image/*,application/pdf" required
                            @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div class="flex flex-col items-center">
                            <div class="p-3 bg-white rounded-xl shadow-sm mb-2 group-hover:scale-105 transition-transform">
                                <i data-lucide="upload-cloud" class="w-5 h-5 text-[#0f4c75]"></i>
                            </div>
                            <p class="text-[13px] font-bold text-[#1a2535]" x-text="fileName ? fileName : 'Pilih dokumen dari galeri/folder'"></p>
                            <p class="text-[11px] font-medium text-[#90a0b4] mt-0.5" x-show="!fileName">Ukuran file maksimal dokumen pendukung 2MB</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold transition-all shadow-md hover:shadow-lg bg-[#0f4c75] hover:bg-[#1b6ca8] text-white text-[14px] pt-4">
                    Kirim Permohonan <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

    </div>
</x-app-layout>