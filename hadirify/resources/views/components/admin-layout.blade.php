<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presensi Mutu Admin - Sistem Presensi Digital SMA Muhammadiyah 7 Yogyakarta</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'Space Mono', monospace; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
    </style>
</head>
<body class="bg-[#f8fafc] antialiased text-[#0b1e36]" x-data="{ sidebarOpen: window.innerWidth >= 768, logoutModal: false }" @resize.window="sidebarOpen = window.innerWidth >= 768">

    <div class="min-h-screen w-full flex overflow-x-hidden">
        
        <!-- Overlay Gelap untuk Mobile saat Sidebar Buka -->
        <div x-show="sidebarOpen && window.innerWidth < 768" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/50 z-40 backdrop-blur-sm transition-opacity"
             x-transition.opacity></div>

        <!-- Sidebar Admin -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 w-64 h-screen bg-[#0b1e36] text-white p-4 flex flex-col justify-between shrink-0 shadow-2xl border-r border-white/10 z-50 transition-transform duration-300 ease-in-out">
            
            <!-- Bagian Atas: Logo & Navigasi Utama -->
            <div class="flex flex-col min-h-0 space-y-4 flex-1 overflow-hidden">
                <!-- Logo & Portal Title -->
                <div class="flex items-center justify-between px-1 pt-1 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-sky-500/20 text-sky-400 rounded-md border border-sky-400/30 flex items-center justify-center shrink-0">
                            <i data-lucide="school" class="w-5 h-5" stroke-width="1.9"></i>
                        </div>
                        <div>
                            <h2 class="text-[15px] font-extrabold tracking-tight leading-tight text-white">Presensi Mutu</h2>
                            <span class="text-[11px] font-medium text-sky-300 block">Panel Administrator</span>
                        </div>
                    </div>
                    <!-- Tombol Close untuk Mobile -->
                    <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-white p-1 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="shrink-0 text-[10px] font-semibold text-sky-200 bg-sky-500/10 border border-sky-400/20 px-2.5 py-1.5 rounded-md tracking-wider text-center uppercase">
                    SMA Muhammadiyah 7
                </div>

                <!-- Navigation Menu -->
                <nav class="space-y-1 overflow-y-auto flex-1 pr-1 custom-scrollbar">
                    <p class="mb-2 mt-1 px-3 text-[10px] font-bold uppercase tracking-widest text-slate-400/80">Menu Utama</p>
                    
                    <a href="/admin/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/dashboard*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/dashboard*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="/admin/akun" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/akun*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="users" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/akun*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Kelola Akun</span>
                    </a>
                    
                    <a href="/admin/kelas" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/kelas*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="building" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/kelas*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Kelas & Jadwal</span>
                    </a>
                    
                    <a href="/admin/koreksi" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/koreksi*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="edit-3" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/koreksi*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Koreksi Absensi</span>
                    </a>
                    
                    <a href="/admin/laporan" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/laporan*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="bar-chart-2" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/laporan*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Laporan Sekolah</span>
                    </a>

                    <a href="/admin/mapel" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] transition-all duration-150 {{ request()->is('admin/mapel*') ? 'bg-sky-500 text-white font-bold shadow-md shadow-sky-500/20' : 'text-slate-300 font-medium hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="book-open" class="w-[18px] h-[18px] shrink-0 {{ request()->is('admin/mapel*') ? 'text-white' : 'text-slate-400' }}" stroke-width="1.9"></i>
                        <span>Mata Pelajaran</span>
                    </a>
                </nav>
            </div>

            <!-- Profile Info & Tombol Keluar (Trigger Modal) -->
            <div class="border-t border-white/10 pt-3 space-y-2.5 shrink-0 mt-3">
                <div class="flex items-center gap-2.5 bg-white/5 p-2.5 rounded-md border border-white/10">
                    <div class="w-9 h-9 rounded-md bg-sky-500 flex items-center justify-center font-extrabold text-white text-xs shadow-sm shrink-0">
                        AD
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-xs font-bold text-white truncate">Administrator</h4>
                        <span class="text-[10px] font-medium text-slate-400 block truncate">SMA Muhammadiyah 7</span>
                    </div>
                </div>
                
                <button @click="logoutModal = true" type="button" class="w-full flex items-center justify-center gap-2 rounded-md border border-white/10 bg-white/5 py-2 text-xs font-semibold text-slate-300 transition-colors hover:bg-rose-500/20 hover:border-rose-400/30 hover:text-rose-200 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4" stroke-width="1.9"></i> Keluar
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main :class="sidebarOpen ? 'md:pl-64' : 'pl-0'" class="flex-1 min-w-0 w-full min-h-screen flex flex-col bg-[#f8fafc] transition-all duration-300">
            <header class="h-14 border-b border-slate-200/70 bg-white/90 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between px-4 md:px-6 gap-4 shrink-0">
                <div class="flex items-center gap-3">
                    <!-- Tombol Hamburger / Buka Tutup Sidebar -->
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-600 bg-slate-100 rounded-md hover:bg-slate-200 hover:text-[#0b1e36] transition-colors cursor-pointer">
                        <i data-lucide="menu" class="w-[18px] h-[18px]"></i>
                    </button>
                    <span class="hidden sm:inline-block text-xs font-semibold text-slate-500">Presensi Mutu • Panel Administrator</span>
                </div>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-emerald-50 border border-emerald-200/70 text-[11px] font-semibold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sesi Keamanan Aktif</span>
                </div>
            </header>
            
            <div class="flex-1 min-w-0 p-4 md:p-6 max-w-[1400px] w-full mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- ================= MODAL KONFIRMASI KELUAR ================= -->
    <div x-show="logoutModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition.opacity
         style="display: none;">
        
        <div @click.outside="logoutModal = false" 
             class="bg-white rounded-lg max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center space-y-4 relative"
             x-transition>
            
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-md flex items-center justify-center mx-auto border border-rose-100">
                <i data-lucide="log-out" class="w-6 h-6"></i>
            </div>

            <div class="space-y-1">
                <h3 class="text-base font-extrabold text-[#0b1e36]">Konfirmasi Keluar</h3>
                <p class="text-xs text-slate-500">Apakah Anda yakin ingin mengakhiri sesi dan keluar dari Panel Administrator?</p>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-2">
                <!-- Tombol Batal -->
                <button @click="logoutModal = false" type="button" class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-md transition-all cursor-pointer">
                    Batal
                </button>
                
                <!-- Form Eksekusi Logout Sebenarnya -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full h-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-md shadow-2xs transition-all cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->

    <script>
        lucide.createIcons();
    </script>
</body>
</html>