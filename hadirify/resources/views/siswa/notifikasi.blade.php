<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        .custom-shadow { box-shadow: 0 4px 20px -2px rgba(0, 97, 150, 0.08); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>

    <div class="max-w-[900px] mx-auto space-y-6 font-['Inter'] animate-in fade-in duration-500">
        
        <div class="bg-white p-6 md:p-8 rounded-[28px] custom-shadow border border-[#bfc7d2] relative overflow-hidden flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#cde5ff]/60 blur-[100px] rounded-full -mr-20 -mt-20 pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="bg-[#007abc] text-white px-3 py-1 rounded-full text-[11px] font-bold mb-3 inline-block uppercase tracking-wider">Pemberitahuan</span>
                <h1 class="text-[32px] font-bold text-[#0b1c30] mb-2 leading-tight">Pusat Notifikasi</h1>
                <p class="text-[14px] text-[#3f4851]">Semua pengumuman dan informasi penting dari guru kelas Anda.</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($notifikasi as $n)
            <div class="group flex gap-4 bg-white p-5 rounded-[24px] border border-[#bfc7d2] custom-shadow hover:-translate-y-1 transition-all duration-300">
                <div class="p-3 bg-[#eff4ff] text-[#006196] rounded-xl border border-[#cde5ff] h-fit">
                    <span class="material-symbols-outlined text-[24px]">campaign</span>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start gap-4">
                        <h3 class="text-[16px] font-bold text-[#0b1c30] group-hover:text-[#006196] transition-colors">{{ $n->judul }}</h3>
                        @if($n->created_at->isToday())
                        <span class="flex h-2.5 w-2.5 relative mt-1 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                        </span>
                        @endif
                    </div>
                    <p class="text-[13.5px] text-[#3f4851] mt-1.5 leading-relaxed">{{ $n->isi }}</p>
                    
                    <div class="mt-4 flex items-center gap-3">
                        <span class="text-[11px] font-bold text-[#707882] flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">person</span> {{ $n->guru->name }}
                        </span>
                        <span class="text-[11px] font-bold text-[#006196] bg-[#eff4ff] px-2.5 py-1 rounded border border-[#bfc7d2]">
                            {{ $n->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-12 text-center flex flex-col items-center justify-center gap-4 bg-white rounded-[24px] border border-[#bfc7d2]">
                <div class="w-16 h-16 bg-[#eff4ff] rounded-full flex items-center justify-center mb-2">
                    <span class="material-symbols-outlined text-[#bfc7d2] text-3xl">notifications_off</span>
                </div>
                <p class="text-[#707882] text-[14px] italic font-medium">Belum ada pengumuman baru untuk kelas ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>