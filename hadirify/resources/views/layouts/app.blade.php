<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hadirify - Sistem Absensi Digital</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-[#f8fafc] text-[#0b1e36] antialiased [font-family:'Plus_Jakarta_Sans',sans-serif]">

    <div class="min-h-screen flex">
        
        <aside class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col bg-gradient-to-b from-[#0b1e36] to-[#0f172a] text-white shadow-2xl border-r border-white/5">
            
            <div class="flex flex-col border-b border-white/10 p-6 gap-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-lg">
                        <i data-lucide="clipboard-check" class="w-6 h-6" stroke-width="2.5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black leading-tight tracking-tight">Hadirify</h2>
                        <p class="text-[9px] tracking-widest text-amber-400/80 uppercase font-extrabold">Portal Utama</p>
                    </div>
                </div>
                <div class="mt-2 text-[10px] font-bold text-sky-300 bg-sky-900/40 border border-sky-400/20 px-2.5 py-1.5 rounded-lg uppercase tracking-wider text-center backdrop-blur-sm">
                    SMA MUH 7 YOGYAKARTA
                </div>
            </div>
            
            <div class="flex items-center gap-3 bg-white/5 p-4 mx-3 my-4 rounded-2xl border border-white/10 shadow-inner backdrop-blur-md">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-100 to-slate-300 font-extrabold text-[#0b1e36] uppercase text-sm shadow-md">
                    {{ substr(Auth::user()->name ?? 'HI', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="truncate text-[13.5px] font-bold text-white">{{ Auth::user()->name ?? 'Pengguna Hadirify' }}</p>
                    <span class="mt-0.5 inline-block rounded-full bg-emerald-500/20 px-2.5 py-[2px] text-[8.5px] font-extrabold uppercase tracking-widest text-emerald-300 border border-emerald-500/30">
                        {{ Auth::user()->role ?? 'Siswa' }}
                    </span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 space-y-1.5 scrollbar-hide">
                <p class="mb-3 mt-2 px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400/70">Menu Navigasi</p>
                
                @php $role = Auth::user()->role ?? 'siswa'; @endphp

                @if($role === 'siswa')
                    <a href="/siswa/dashboard" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->is('siswa/dashboard*') ? 'bg-white/10 font-bold text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white hover:translate-x-1' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 transition-colors duration-300 {{ request()->is('siswa/dashboard*') ? 'text-amber-400' : 'group-hover:text-amber-400/70' }}"></i> 
                        <span class="text-[13.5px]">Dashboard</span>
                    </a>
                    
                    <a href="/siswa/scan-qr" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->is('siswa/scan-qr*') ? 'bg-white/10 font-bold text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white hover:translate-x-1' }}">
                        <i data-lucide="qr-code" class="w-5 h-5 transition-colors duration-300 {{ request()->is('siswa/scan-qr*') ? 'text-amber-400' : 'group-hover:text-amber-400/70' }}"></i> 
                        <span class="text-[13.5px]">Scan QR Absen</span>
                    </a>

                    <a href="/siswa/rekap" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->is('siswa/rekap*') ? 'bg-white/10 font-bold text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white hover:translate-x-1' }}">
                        <i data-lucide="history" class="w-5 h-5 transition-colors duration-300 {{ request()->is('siswa/rekap*') ? 'text-amber-400' : 'group-hover:text-amber-400/70' }}"></i> 
                        <span class="text-[13.5px]">Rekap Kehadiran</span>
                    </a>

                    <a href="/siswa/izin" class="group flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-300 {{ request()->is('siswa/izin*') ? 'bg-white/10 font-bold text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white hover:translate-x-1' }}">
                        <i data-lucide="file-signature" class="w-5 h-5 transition-colors duration-300 {{ request()->is('siswa/izin*') ? 'text-amber-400' : 'group-hover:text-amber-400/70' }}"></i> 
                        <span class="text-[13.5px]">Ajukan Izin</span>
                    </a>

                    <a href="/siswa/notifikasi" class="group flex items-center justify-between rounded-xl px-4 py-3 transition-all duration-300 {{ request()->is('siswa/notifikasi*') ? 'bg-white/10 font-bold text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white hover:translate-x-1' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="bell" class="w-5 h-5 transition-colors duration-300 {{ request()->is('siswa/notifikasi*') ? 'text-amber-400' : 'group-hover:text-amber-400/70' }}"></i> 
                            <span class="text-[13.5px]">Notifikasi</span>
                        </div>
                        @php
    // Menghitung jumlah pengumuman berdasarkan kelas siswa yang login
    $notifCount = \App\Models\Pengumuman::where('kelas_id', Auth::user()->id_kelas)->count();
@endphp

@if($notifCount > 0)
    <span class="bg-amber-500 text-[#0b1e36] text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm">
        {{ $notifCount }}
    </span>
@endif
                    </a>
                @endif
            </nav>

            <div class="border-t border-white/10 p-5 bg-[#0b1e36]/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="group flex w-full items-center justify-center gap-2 rounded-xl border border-rose-500/20 bg-rose-500/10 p-3 text-[13.5px] font-bold text-rose-400 transition-all duration-300 hover:bg-rose-500 hover:text-white hover:shadow-lg hover:shadow-rose-500/20 cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4 transition-transform group-hover:-translate-x-1"></i> Keluar Portal
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 pl-64 min-h-screen bg-[#f8fafc]">
            <header class="h-20 border-b border-slate-200 bg-white/70 backdrop-blur-xl sticky top-0 z-30 flex items-center justify-between px-8">
                <div></div>
                
                <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                    </span>
                    <span class="text-[12px] font-extrabold text-slate-600 uppercase tracking-widest">Sesi Aktif</span>
                </div>
            </header>
            
            <div class="p-8 max-w-[1400px] mx-auto animate-in fade-in slide-in-from-bottom-4 duration-500 ease-out">
                {{ $slot }}
            </div>
        </main>

    </div>

    <script>
        // Render semua icon Lucide
        lucide.createIcons();
    </script>
</body>
</html>