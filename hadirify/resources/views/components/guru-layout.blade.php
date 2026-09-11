<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Mutu - Portal Pendidik SMA Muhammadiyah 7 Yogyakarta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'Space Mono', monospace; }
        /* Custom scrollbar halus untuk navigasi sidebar jika layar pendek */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-[#f8fafc] antialiased text-[#0b1e36] overflow-hidden">

    <div class="h-screen w-full flex overflow-hidden">
        
        <!-- Sidebar Guru (Fixed Full Screen Height) -->
        <aside class="fixed inset-y-0 left-0 w-[280px] h-screen bg-[#0b1e36] text-white p-6 flex flex-col justify-between shrink-0 shadow-2xl border-r border-white/5 z-40">
            
            <!-- Bagian Atas: Logo & Navigasi Utama -->
            <div class="flex flex-col min-h-0 space-y-6 flex-1 overflow-hidden">
                <!-- Logo & Portal Title -->
                <div class="flex flex-col gap-2 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-amber-500/20 text-amber-400 rounded-xl border border-amber-500/30">
                            <i data-lucide="graduation-cap" class="w-6 h-6" stroke-width="2"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight leading-none text-white">Presensi Mutu</h2>
                            <span class="text-[10px] font-semibold text-amber-300 mt-1 block">Portal Pendidik</span>
                        </div>
                    </div>
                    <div class="mt-2 text-[9.5px] font-bold text-amber-300 bg-amber-500/10 border border-amber-400/20 px-2.5 py-1 rounded-lg tracking-wider text-center uppercase">
                        SMA MUHAMMADIYAH 7
                    </div>
                </div>

                <!-- Navigation Menu (Scroll Mandiri Jika Layar Pendek) -->
                <nav class="space-y-1 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <p class="mb-2 mt-2 px-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Pendidik</p>
                    
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
                            <span class="bg-amber-500/20 text-amber-300 border border-amber-500/30 text-[10px] px-2 py-0.5 rounded-full font-semibold">
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

            <!-- Profile Info & Log out (Selalu Terlihat di Bagian Bawah) -->
            <div class="border-t border-white/10 pt-4 space-y-3 shrink-0 mt-4">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-[#0b1e36] flex items-center justify-center font-black text-sm shadow-sm shrink-0">
                        GR
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-[12.5px] font-bold text-white truncate">{{ Auth::user()->name ?? 'Pendidik' }}</h4>
                        <span class="text-[10px] font-medium text-slate-400 block truncate">Guru SMAM 7</span>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 p-2.5 text-[12px] font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area (Scroll Mandiri Vertikal di Sebelah Kanan) -->
        <main class="flex-1 pl-[280px] h-screen overflow-y-auto flex flex-col bg-[#f8fafc]">
            <header class="h-16 border-b border-slate-200/60 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-end px-10 gap-4 shrink-0">
                <div class="text-[12px] font-semibold text-slate-500 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sesi Pendidik Aktif
                </div>
            </header>
            
            <div class="flex-1 p-10 max-w-[1400px]">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>