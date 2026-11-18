<x-guru-layout>
    <!-- Container Utama: Fixed Height agar Header diam & Konten bisa di-scroll -->
    <div class="animate-in fade-in duration-700 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white border border-slate-200/80 rounded-lg shadow-2xs">
            <div class="p-5 sm:px-6 sm:py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-slate-900 flex items-center justify-center rounded-md shadow-2xs">
                        <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight">Pusat Pengumuman</h2>
                        <p class="text-[11px] text-slate-500 font-medium italic">Siarkan informasi & instruksi akademik ke siswa</p>
                    </div>
                </div>

                <!-- Clock & Date -->
                <div class="flex items-center gap-3 px-3 py-1.5 bg-slate-50/80 rounded-md border border-slate-100">
                    <div class="text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">{{ now()->translatedFormat('d M Y') }}</p>
                        <p id="realtime-clock" class="text-xs font-bold text-[#0b1e36] font-mono leading-none">00:00:00</p>
                    </div>
                    <div class="w-[1px] h-4 bg-slate-200"></div>
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md font-bold text-xs flex items-center gap-2 animate-in zoom-in">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 2: SCROLLABLE CONTENT ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI: FORM TULIS -->
                <div class="lg:col-span-5 bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i data-lucide="pen-tool" class="w-4 h-4 text-sky-500"></i>
                        <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Buat Pesan Baru</h3>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('guru.pengumuman.send') }}" method="POST" class="space-y-4"
                            @input="if ($el.dataset.validationAnchor) $el.elements[$el.dataset.validationAnchor].setCustomValidity('')"
                            @change="if ($el.dataset.validationAnchor) $el.elements[$el.dataset.validationAnchor].setCustomValidity('')"
                            @if($errors->any())
                                x-init="$nextTick(() => {
                                    const fieldName = @js($errors->keys()[0]);
                                    const field = $el.elements[fieldName];
                                    if (field) {
                                        $el.dataset.validationAnchor = fieldName;
                                        field.setCustomValidity(@js($errors->first()));
                                        setTimeout(() => field.reportValidity(), 250);
                                    }
                                })"
                            @endif>
                            @csrf
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Target Kelas</label>
                                <div class="relative">
                                    <select name="kelas_id" required class="w-full pl-3 pr-10 py-2.5 bg-white border border-slate-200 rounded-md text-xs font-bold text-slate-700 outline-none focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 transition-all cursor-pointer">
                                        <option value="">-- Pilih Kelas Penerima --</option>
                                        @foreach($kelas as $k)
                                            <option value="{{ $k->id }}" @selected(old('kelas_id') == $k->id)>{{ $k->nama_kelas }} ({{ $k->tahun_ajaran }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Subjek / Topik</label>
                                <input type="text" name="judul" value="{{ old('judul') }}" maxlength="200" placeholder="Contoh: Info Tugas Pertemuan 5" required
                                       class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-md text-xs font-bold text-slate-700 outline-none focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Rincian Pesan</label>
                                <textarea name="isi" placeholder="Tulis instruksi lengkap di sini..." required maxlength="10000" rows="6"
                                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-md text-xs font-medium text-slate-600 outline-none focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 transition-all resize-none">{{ old('isi') }}</textarea>
                            </div>

                            <button type="submit" class="w-full h-11 bg-slate-900 hover:bg-black text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-md shadow-2xs flex items-center justify-center gap-3 transition-all active:scale-95">
                                <i data-lucide="send" class="w-4 h-4 stroke-[2.5px]"></i>
                                Siarkan Pesan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KOLOM KANAN: RIWAYAT -->
                <div class="lg:col-span-7 bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4 text-slate-400"></i>
                            <h3 class="text-[11px] font-black text-slate-800 uppercase tracking-widest">Riwayat Siaran</h3>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($pengumumans ?? [] as $announcement)
                            <div class="p-6 hover:bg-slate-50/50 transition-colors group">
                                <div class="flex items-center justify-between gap-4 mb-3">
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-md border border-slate-200 uppercase">
                                        Kelas: {{ $announcement->kelas->nama_kelas ?? 'Umum' }}
                                    </span>
                                    <div class="flex items-center gap-1.5 text-slate-400">
                                        <i data-lucide="clock-3" class="w-3 h-3"></i>
                                        <span class="text-[10px] font-bold">{{ \Carbon\Carbon::parse($announcement->created_at)->locale('id')->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 tracking-tight group-hover:text-sky-600 transition-colors">
                                    {{ $announcement->judul }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-2 leading-relaxed whitespace-pre-line">
                                    {{ $announcement->isi }}
                                </p>
                            </div>
                        @empty
                            <div class="py-20 text-center opacity-40">
                                <div class="flex flex-col items-center">
                                    <i data-lucide="megaphone-off" class="w-12 h-12 mb-3 text-slate-400"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Belum ada pengumuman terkirim</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Jam Realtime -->
    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('realtime-clock').textContent = 
                now.getHours().toString().padStart(2, '0') + ':' + 
                now.getMinutes().toString().padStart(2, '0') + ':' + 
                now.getSeconds().toString().padStart(2, '0');
        }
        setInterval(updateClock, 1000); updateClock();
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-guru-layout>