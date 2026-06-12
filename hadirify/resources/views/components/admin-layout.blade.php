<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hadirify Admin - Sistem Presensi Digital SMA Muhammadiyah 7 Yogyakarta</title>
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
        
        <!-- Sidebar Admin -->
        <aside class="w-[280px] bg-[#0b1e36] text-white p-6 flex flex-col justify-between shrink-0 shadow-2xl border-r border-white/5">
            <div class="space-y-6">
                <!-- Logo & Portal Title -->
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-white/10 rounded-xl text-rose-500">
                            <i data-lucide="shield-check" class="w-6 h-6" stroke-width="2.5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-black tracking-tight leading-none">Hadirify</h2>
                            <span class="text-[9px] font-extrabold text-rose-400 tracking-widest uppercase mt-1 block font-mono">Super Admin</span>
                        </div>
                    </div>
                    <div class="mt-2 text-[9px] font-bold text-rose-400 bg-rose-500/10 border border-rose-400/20 px-2.5 py-1 rounded-lg uppercase tracking-wider text-center">
                        SMA MUH 7 YOGYAKARTA
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-1">
                    <p class="mb-2 mt-2 px-4 text-[9px] font-extrabold uppercase tracking-widest text-white/30">Menu Admin</p>
                    
                    <a href="/admin/dashboard" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('admin/dashboard*') ? 'bg-white/10 text-white shadow-md border-l-4 border-rose-500 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 {{ request()->is('admin/dashboard*') ? 'text-rose-500' : '' }}"></i> Dashboard
                    </a>
                    
                    <a href="/admin/akun" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('admin/akun*') ? 'bg-white/10 text-white shadow-md border-l-4 border-rose-500 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="users" class="w-4.5 h-4.5 {{ request()->is('admin/akun*') ? 'text-rose-500' : '' }}"></i> Kelola Akun
                    </a>
                    
                    <a href="/admin/kelas" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('admin/kelas*') ? 'bg-white/10 text-white shadow-md border-l-4 border-rose-500 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="building" class="w-4.5 h-4.5 {{ request()->is('admin/kelas*') ? 'text-rose-500' : '' }}"></i> Kelas & Jadwal
                    </a>
                    
                    <a href="/admin/koreksi" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('admin/koreksi*') ? 'bg-white/10 text-white shadow-md border-l-4 border-rose-500 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="edit" class="w-4.5 h-4.5 {{ request()->is('admin/koreksi*') ? 'text-rose-500' : '' }}"></i> Koreksi Absensi
                    </a>
                    
                    <a href="/admin/laporan" class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-bold text-[13.5px] transition-all duration-200 {{ request()->is('admin/laporan*') ? 'bg-white/10 text-white shadow-md border-l-4 border-rose-500 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="bar-chart-2" class="w-4.5 h-4.5 {{ request()->is('admin/laporan*') ? 'text-rose-500' : '' }}"></i> Laporan Sekolah
                    </a>
                </nav>
            </div>

            <!-- Profile Info & Log out -->
            <div class="border-t border-white/10 pt-4 space-y-4">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-xl border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-rose-500 flex items-center justify-center font-extrabold text-white text-sm shadow-md">
                        AD
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-[12px] font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</h4>
                        <span class="text-[9px] font-medium text-slate-400 block truncate">Tim IT Sekolah</span>
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
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    Konsol Keamanan Aktif
                </div>
            </header>
            
            <div class="flex-1 p-10 overflow-y-auto max-w-[1400px]">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>