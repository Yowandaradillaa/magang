<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hadirify Guru - Sistem Presensi Digital SMA Muhammadiyah 7 Yogyakarta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-[#f8fafc] antialiased text-[#0b1e36]">

    <div class="min-h-screen flex">
        
        <!-- Sidebar Guru -->
        <aside class="w-[280px] bg-[#0b1e36] text-white p-6 flex flex-col justify-between shrink-0 shadow-2xl border-r border-white/5">
            <div class="space-y-6">
                <!-- Logo & Portal Title -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-white/10 rounded-xl text-amber-400">
                            <i data-lucide="qr-code" class="w-6 h-6" stroke-width="2.5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight leading-none">Hadirify</h2>
                            <span class="text-[9px] font-extrabold text-[#00b4d8] tracking-widest uppercase mt-1 block">Portal Pendidik</span>
                        </div>
                    </div>
                    <div class="mt-2 text-[9px] font-bold text-amber-400 bg-amber-500/10 border border-amber-400/20 px-2.5 py-1 rounded-lg uppercase tracking-wider text-center">
                        SMA MUH 7 YOGYAKARTA
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-1">
                    <p class="mb-2 mt-2 px-4 text-[9px] font-extrabold uppercase tracking-widest text-white/30">Menu Guru</p>
                    
                    <a href="/guru/dashboard" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/dashboard*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 {{ request()->is('guru/dashboard*') ? 'text-amber-400' : '' }}"></i> Dashboard
                    </a>
                    
                    <a href="/guru/qr" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/qr*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="qr-code" class="w-4.5 h-4.5 {{ request()->is('guru/qr*') ? 'text-amber-400' : '' }}"></i> QR Absensi
                    </a>
                    
                    <a href="/guru/manual" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/manual*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="edit" class="w-4.5 h-4.5 {{ request()->is('guru/manual*') ? 'text-amber-400' : '' }}"></i> Input Manual
                    </a>
                    
                    <a href="/guru/izin" class="flex items-center justify-between px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/izin*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3.5">
                            <i data-lucide="clipboard-list" class="w-4.5 h-4.5 {{ request()->is('guru/izin*') ? 'text-amber-400' : '' }}"></i> Izin Siswa
                        </div>
                        @php
                            $pendingCount = \App\Models\PengajuanIzin::where('status', 'Pending')->count();
                        @endphp

                        @if($pendingCount > 0)
                            <span class="bg-rose-500 text-white text-[9px] px-2 py-0.5 rounded-full font-black">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="/guru/rekap" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/rekap*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="bar-chart-2" class="w-4.5 h-4.5 {{ request()->is('guru/rekap*') ? 'text-amber-400' : '' }}"></i> Rekap & Export
                    </a>
                    
                    <a href="/guru/pengumuman" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('guru/pengumuman*') ? 'bg-white/10 text-white shadow-md border-l-4 border-amber-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="megaphone" class="w-4.5 h-4.5 {{ request()->is('guru/pengumuman*') ? 'text-amber-400' : '' }}"></i> Pengumuman
                    </a>

                </nav>
            </div>

            <!-- Profile Info & Log out -->
            <div class="border-t border-white/10 pt-4 space-y-4">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-amber-400 flex items-center justify-center font-extrabold text-[#0b1e36] text-sm shadow-md">
                        GR
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-[12px] font-bold text-white truncate">{{ Auth::user()->name ?? 'Pendidik' }}</h4>
                        <span class="text-[9px] font-medium text-slate-400 block truncate">NIP/NUPTK Aktif</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-rose-500/25 bg-rose-50/5 p-2.5 text-[12px] font-bold text-rose-300 transition-colors hover:bg-rose-500/20 cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar Portal
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-h-screen flex flex-col">
            <header class="h-16 border-b border-slate-100 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-end px-10 gap-4">
                <div class="text-[12px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sesi Pendidik Aktif
                </div>
            </header>
            
            <div class="flex-1 p-10 overflow-y-auto max-w-[1400px]">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi ikon Lucide secara global
        lucide.createIcons();
    </script>
</body>
</html>