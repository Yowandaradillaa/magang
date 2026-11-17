<x-app-layout>
    <!-- Container Utama: Alpine.js untuk kontrol Modal -->
    <div x-data="{ 
            selectedNotif: null, 
            showDetail: false,
            openDetail(notif) {
                this.selectedNotif = notif;
                this.showDetail = true;
            }
         }" 
         class="animate-in fade-in duration-700 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)] max-w-4xl mx-auto">
        
        <!-- ================= SECTION 1: HEADER (FIXED - GAYA KOTAK TEGAS) ================= -->
        <div class="flex-none bg-white border border-slate-200/70 rounded-lg shadow-sm">
            <div class="p-4 sm:px-6 sm:py-3.5 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0b1e36] flex items-center justify-center rounded-md shadow-sm">
                        <i data-lucide="megaphone" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="space-y-0.5">
                        <h2 class="text-base font-extrabold text-[#0b1e36] tracking-tight">Pusat Notifikasi</h2>
                        <p class="text-[11px] text-slate-500 font-medium">Informasi & pengumuman akademik terbaru</p>
                    </div>
                </div>

                <!-- Clock -->
                <p id="realtime-clock" class="hidden sm:block text-xs font-bold text-[#0b1e36] font-mono bg-slate-50 px-3 py-1.5 rounded-md border border-slate-200/70">
                    00:00:00
                </p>
            </div>
        </div>

        <!-- ================= SECTION 2: LIST (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 overflow-y-auto no-scrollbar pb-10 space-y-3">
            @forelse($notifikasi as $n)
            <!-- Card Klik-able Bergaya Kotak Tegas & Berwibawa -->
            <div @click="openDetail({ 
                    judul: '{{ $n->judul }}', 
                    isi: '{{ addslashes(str_replace(["\r", "\n"], ' ', $n->isi)) }}', 
                    guru: '{{ $n->guru->name }}', 
                    waktu: '{{ $n->created_at->diffForHumans() }}',
                    tanggal: '{{ $n->created_at->translatedFormat('d F Y, H:i') }}'
                 })"
                 class="bg-white rounded-lg border border-slate-200/80 shadow-xs group hover:border-slate-300 hover:shadow-sm transition-all cursor-pointer">
                
                <div class="p-4.5 sm:p-5 flex gap-4 items-start">
                    <div class="hidden sm:flex shrink-0 w-9 h-9 bg-slate-100/80 text-slate-500 rounded-md items-center justify-center group-hover:bg-sky-50 group-hover:text-sky-600 transition-colors mt-0.5">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-3 mb-1.5">
                            <h3 class="text-sm font-extrabold text-[#0b1e36] group-hover:text-sky-600 transition-colors leading-snug">
                                {{ $n->judul }}
                            </h3>
                            @if($n->created_at->isToday())
                            <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[9px] font-extrabold rounded-md border border-rose-200 uppercase tracking-wider shrink-0">Baru</span>
                            @endif
                        </div>
                        
                        <!-- Snippet / Cuplikan Teks -->
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-normal">
                            {{ $n->isi }}
                        </p>
                        
                        <div class="mt-3.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i data-lucide="user" class="w-3 h-3"></i> {{ $n->guru->name }}
                            </span>
                            <div class="flex items-center gap-1.5 text-slate-400">
                                <span class="text-[10px] font-mono">{{ $n->created_at->diffForHumans() }}</span>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-slate-600 group-hover:translate-x-0.5 transition-all"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-24 text-center opacity-30">
                <i data-lucide="bell-off" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="text-[10px] font-black uppercase tracking-widest italic">Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>

        <!-- ================= SECTION 3: MODAL DETAIL (MODERN) ================= -->
        <template x-teleport="body">
            <div x-show="showDetail" x-cloak
                 class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-end="opacity-0">
                
                <div @click.away="showDetail = false"
                     x-show="showDetail"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-white/20">
                    
                    <!-- Header Modal -->
                    <div class="bg-slate-50 p-6 border-b border-slate-100 flex justify-between items-start">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-sky-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-sky-100">
                                <i data-lucide="megaphone" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 leading-tight" x-text="selectedNotif?.judul"></h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1" x-text="selectedNotif?.tanggal"></p>
                            </div>
                        </div>
                        <button @click="showDetail = false" class="text-slate-300 hover:text-rose-500 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <!-- Body Modal -->
                    <div class="p-8">
                        <div class="prose prose-slate max-w-none text-sm text-slate-600 leading-relaxed italic border-l-4 border-slate-100 pl-4 py-1 mb-8" x-text="selectedNotif?.isi"></div>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center">
                                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase leading-none">Pengirim</p>
                                    <p class="text-xs font-bold text-slate-800" x-text="selectedNotif?.guru"></p>
                                </div>
                            </div>
                            <button @click="showDetail = false" class="px-5 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-black transition-all">
                                Mengerti
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Script Jam & Icons -->
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