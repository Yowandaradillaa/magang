<x-app-layout>
    <!-- Container Utama: Fixed Header & Scrollable Body -->
    <div x-data="{ 
            jenis: 'Sakit', 
            fileName: '', 
            showModal: @json(session('success') || session('error')), 
            isSuccess: @json((bool) session('success')) 
         }" 
         x-init="if(showModal) setTimeout(() => showModal = false, 4000)"
         class="animate-in fade-in duration-700 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)] max-w-3xl mx-auto">

        <!-- ================= SECTION 1: POPUP NOTIFIKASI (MODERN) ================= -->
        <template x-teleport="body">
            <div x-show="showModal" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4 transition-all">
                <div @click.away="showModal = false"
                     x-show="showModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     class="bg-white rounded-xl p-8 max-w-sm w-full text-center shadow-2xl border border-white/20 relative overflow-hidden">
                    
                    <!-- Top Accent Line -->
                    <div class="absolute top-0 left-0 right-0 h-1.5" :class="isSuccess ? 'bg-emerald-500' : 'bg-rose-500'"></div>

                    <div class="mx-auto mb-5 flex items-center justify-center w-16 h-16 rounded-full"
                         :class="isSuccess ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-500'">
                        <i :data-lucide="isSuccess ? 'check-circle' : 'alert-circle'" class="w-10 h-10 stroke-[2.5px]"></i>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 tracking-tight" x-text="isSuccess ? 'Berhasil Terkirim' : 'Gagal Mengirim'"></h3>
                    <p class="text-[13px] font-medium text-slate-500 mt-2 leading-relaxed">
                        {{ session('success') ?? session('error') }}
                    </p>
                    
                    <button @click="showModal = false"
                            class="mt-8 w-full py-3 rounded-lg font-black text-[11px] uppercase tracking-widest text-white transition-all active:scale-95 shadow-lg"
                            :class="isSuccess ? 'bg-emerald-600 shadow-emerald-100' : 'bg-rose-600 shadow-rose-100'">
                        Tutup Jendela
                    </button>
                </div>
            </div>
        </template>

        <!-- ================= SECTION 2: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/50 rounded-xl shadow-sm">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-500 flex items-center justify-center rounded-lg shadow-lg shadow-amber-100">
                        <i data-lucide="file-signature" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight leading-none">Formulir Izin</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">Silakan lengkapi alasan & bukti sah</p>
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

        <!-- ================= SECTION 3: FORM CONTENT (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10">
            <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm relative overflow-hidden">
                <!-- Left Accent Line -->
                <div class="absolute left-0 top-0 bottom-0 w-[3px] bg-amber-500"></div>
                
                <form method="POST" action="{{ route('siswa.izin.ajukan') }}" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="jenis" :value="jenis">

                    <!-- Category Toggles -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kategori Absensi</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="jenis = 'Sakit'"
                                :class="jenis === 'Sakit' ? 'bg-sky-600 text-white border-sky-700 shadow-lg shadow-sky-100' : 'bg-slate-50 text-slate-400 border-slate-200 hover:bg-slate-100'"
                                class="flex items-center justify-center gap-2.5 py-3.5 rounded-lg border font-bold text-xs transition-all active:scale-95 outline-none">
                                <i data-lucide="heart-pulse" class="w-4 h-4"></i> Kondisi Sakit
                            </button>
                            <button type="button" @click="jenis = 'Izin'"
                                :class="jenis === 'Izin' ? 'bg-amber-500 text-white border-amber-600 shadow-lg shadow-amber-100' : 'bg-slate-50 text-slate-400 border-slate-200 hover:bg-slate-100'"
                                class="flex items-center justify-center gap-2.5 py-3.5 rounded-lg border font-bold text-xs transition-all active:scale-95 outline-none">
                                <i data-lucide="info" class="w-4 h-4"></i> Keperluan Izin
                            </button>
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Mulai Tanggal</label>
                            <input type="date" name="tanggal_mulai" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 outline-none focus:border-amber-500 focus:bg-white transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                            <input type="date" name="tanggal_selesai" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 outline-none focus:border-amber-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <!-- Alasan Detail -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Alasan</label>
                        <textarea name="alasan" rows="4" placeholder="Tulis rincian keterangan Anda..." required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-600 outline-none focus:border-amber-500 focus:bg-white transition-all resize-none"></textarea>
                    </div>

                    <!-- File Upload Dropzone -->
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Lampiran Dokumen (Foto/PDF)</label>
                        <div class="relative group">
                            <input type="file" name="file_surat" accept="image/*,application/pdf"
                                @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <div class="p-8 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 group-hover:bg-slate-100 group-hover:border-amber-400 transition-all text-center">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                    <i data-lucide="upload-cloud" class="w-6 h-6 text-slate-300 group-hover:text-amber-500"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-600" x-text="fileName ? fileName : 'Klik atau seret berkas bukti di sini'"></p>
                                <p class="text-[9px] text-slate-400 mt-1 uppercase font-black tracking-tighter">Maksimal 2MB (JPG, PNG, PDF)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full h-12 bg-slate-900 hover:bg-black text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-lg shadow-lg shadow-slate-200 flex items-center justify-center gap-3 transition-all active:scale-95">
                        <i data-lucide="send" class="w-4 h-4 stroke-[2.5px]"></i>
                        Kirim Permohonan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Realtime Clock -->
    <script>
        setInterval(() => {
            const now = new Date();
            const el = document.getElementById('realtime-clock');
            if(el) el.textContent = now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0')+':'+now.getSeconds().toString().padStart(2,'0');
        }, 1000);
        lucide.createIcons();
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>