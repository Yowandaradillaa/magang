<x-guru-layout>
    <div class="animate-in fade-in duration-300 space-y-8">
        
        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-[#0b1e36]/10 text-[#0b1e36] rounded-lg">
                        <i data-lucide="megaphone" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Pusat Pengumuman</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Siarkan informasi, tugas, atau instruksi akademik langsung kepada siswa di kelas Anda</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold rounded-xl flex items-center gap-2 animate-bounce">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 12 12 14 14"></polyline><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Form Tulis Pengumuman -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-[#0b1e36] flex items-center gap-2">
                        <i data-lucide="pen-square" class="w-4 h-4 text-amber-500"></i>
                        Tulis Pengumuman Baru
                    </h3>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('guru.pengumuman.send') }}" method="POST" class="space-y-5">
                        @csrf

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Target Kelas</label>
                            <div class="relative">
                                <select name="kelas_id" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] appearance-none bg-white transition-all duration-200">
                                    <option value="">-- Pilih Kelas Penerima --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tahun_ajaran }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Judul Pengumuman</label>
                            <input type="text" name="judul" placeholder="Ketik judul pesan atau topik pengumuman..." required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-semibold text-[#0b1e36] placeholder-slate-300 transition-all duration-200">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-wider">Isi Pengumuman</label>
                            <textarea name="isi" placeholder="Tulis rincian pesan atau instruksi tugas di sini secara lengkap..." required rows="6"
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-[#0b1e36] focus:ring-4 focus:ring-[#0b1e36]/10 outline-none text-[13.5px] font-medium text-[#0b1e36] placeholder-slate-300 transition-all duration-200 resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-[13.5px] font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim & Siarkan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Pengumuman -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-[#0b1e36] flex items-center gap-2">
                        <i data-lucide="history" class="w-4 h-4 text-amber-500"></i>
                        Riwayat Pengumuman Terkirim
                    </h3>
                </div>

                <div class="divide-y divide-slate-100 p-6 space-y-5 overflow-y-auto max-h-[560px]">
                    @forelse($pengumumans ?? [] as $announcement)
                        <div class="pt-5 first:pt-0 group">
                            <div class="flex items-start justify-between gap-4">
                                <span class="px-2.5 py-1 bg-[#0b1e36]/5 text-[#0b1e36] text-[10px] font-extrabold rounded-lg uppercase tracking-wider border border-[#0b1e36]/10">
                                    Kelas: {{ $announcement->kelas->nama_kelas ?? 'Umum' }}
                                </span>
                                <span class="text-[11px] font-mono text-slate-400 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    {{ \Carbon\Carbon::parse($announcement->created_at)->locale('id')->diffForHumans() }}
                                </span>
                            </div>
                            <h4 class="text-[15px] font-extrabold text-[#0b1e36] mt-2 group-hover:text-amber-600 transition-colors">
                                {{ $announcement->judul }}
                            </h4>
                            <p class="text-[13px] text-slate-500 mt-1.5 leading-relaxed whitespace-pre-line">
                                {{ $announcement->isi }}
                            </p>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="p-4 bg-slate-50 rounded-full border border-slate-100 text-slate-300">
                                    <i data-lucide="megaphone-off" class="w-10 h-10"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-[#0b1e36] text-sm">Belum Ada Pengumuman</h4>
                                    <p class="text-xs text-slate-400 mt-1 max-w-[280px]">Mulai dengan mengetik pesan baru di sebelah kiri untuk disiarkan.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-guru-layout>