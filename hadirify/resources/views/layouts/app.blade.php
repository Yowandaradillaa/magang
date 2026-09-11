<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Presensi Mutu - Portal Siswa SMA Muhammadiyah 7 Yogyakarta</title>

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
    </style>
</head>

<body class="bg-[#f8fafc] text-[#0b1e36] antialiased overflow-hidden">

    <div class="h-screen w-full flex overflow-hidden">
        
        <!-- Sidebar Siswa (Fixed / Sticky Screen Height) -->
        <aside id="sidebar" class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col bg-[#0b1e36] text-white shadow-2xl border-r border-white/5 transition-transform duration-300 transform -translate-x-full md:translate-x-0 justify-between">
            
            <div class="flex flex-col min-h-0 space-y-4 flex-1 overflow-hidden">
                
                <!-- Logo & Portal Title -->
                <div class="flex flex-col border-b border-white/10 p-5 gap-2 relative shrink-0">
                    <button id="close-sidebar" class="absolute top-4 right-4 text-white/50 hover:text-white md:hidden">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>

                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-sky-500/20 text-sky-400 rounded-xl border border-sky-500/30 shrink-0">
                            <i data-lucide="school" class="w-6 h-6" stroke-width="2"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-black leading-tight tracking-tight text-white">Presensi Mutu</h2>
                            <p class="text-[10px] text-sky-300 font-semibold mt-0.5">Portal Siswa</p>
                        </div>
                    </div>
                    <div class="mt-2 text-[9.5px] font-bold text-sky-300 bg-sky-500/10 border border-sky-400/20 px-2 py-1 rounded-lg uppercase tracking-wider text-center">
                        SMA MUHAMMADIYAH 7
                    </div>
                </div>
                
                <!-- Profil Singkat Siswa -->
                <div class="flex items-center gap-3 bg-white/5 p-3.5 mx-3 rounded-xl border border-white/5 shrink-0">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-600 font-extrabold text-white text-xs shadow-sm">
                        {{ substr(Auth::user()->name ?? 'SW', 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name ?? 'Siswa' }}</p>
                        <span class="mt-0.5 inline-block text-[9.5px] font-medium text-slate-400 truncate">
                            Kelas {{ Auth::user()->kelas->nama_kelas ?? 'Siswa Active' }}
                        </span>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 overflow-y-auto px-3 space-y-1 custom-scrollbar">
                    <p class="mb-2 mt-1 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Utam</p>
                    
                    @php $role = Auth::user()->role ?? 'siswa'; @endphp

                    @if($role === 'siswa')
                        <a href="/siswa/dashboard" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 transition-all duration-200 {{ request()->is('siswa/dashboard*') ? 'bg-white/10 font-bold text-white shadow-sm border-l-4 border-sky-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-4.5 h-4.5 {{ request()->is('siswa/dashboard*') ? 'text-sky-400' : '' }}"></i> 
                            <span class="text-xs font-semibold">Dashboard</span>
                        </a>
                        
                        <a href="/siswa/scan-qr" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 transition-all duration-200 {{ request()->is('siswa/scan-qr*') ? 'bg-white/10 font-bold text-white shadow-sm border-l-4 border-sky-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="qr-code" class="w-4.5 h-4.5 {{ request()->is('siswa/scan-qr*') ? 'text-sky-400' : '' }}"></i> 
                            <span class="text-xs font-semibold">Scan QR Absen</span>
                        </a>

                        <a href="/siswa/rekap" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 transition-all duration-200 {{ request()->is('siswa/rekap*') ? 'bg-white/10 font-bold text-white shadow-sm border-l-4 border-sky-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="history" class="w-4.5 h-4.5 {{ request()->is('siswa/rekap*') ? 'text-sky-400' : '' }}"></i> 
                            <span class="text-xs font-semibold">Rekap Kehadiran</span>
                        </a>

                        <a href="/siswa/izin" class="group flex items-center gap-3 rounded-xl px-3.5 py-2.5 transition-all duration-200 {{ request()->is('siswa/izin*') ? 'bg-white/10 font-bold text-white shadow-sm border-l-4 border-sky-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="file-signature" class="w-4.5 h-4.5 {{ request()->is('siswa/izin*') ? 'text-sky-400' : '' }}"></i> 
                            <span class="text-xs font-semibold">Ajukan Izin</span>
                        </a>

                        <a href="/siswa/notifikasi" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition-all duration-200 {{ request()->is('siswa/notifikasi*') ? 'bg-white/10 font-bold text-white shadow-sm border-l-4 border-sky-400 pl-3' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="bell" class="w-4.5 h-4.5 {{ request()->is('siswa/notifikasi*') ? 'text-sky-400' : '' }}"></i> 
                                <span class="text-xs font-semibold">Notifikasi</span>
                            </div>
                            @php
                                $user = Auth::user();
                                $notifCount = \App\Models\Pengumuman::where('kelas_id', $user->id_kelas)
                                    ->where('created_at', '>', $user->notification_last_viewed_at ?? '2000-01-01')
                                    ->count();
                            @endphp

                            @if($notifCount > 0)
                                <span class="bg-sky-500/20 text-sky-300 border border-sky-500/30 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                    {{ $notifCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Bottom Logout Section (Pinned) -->
            <div class="border-t border-white/10 p-4 shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="group flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 p-2.5 text-xs font-semibold text-slate-300 transition-colors hover:bg-white/10 hover:text-white cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-[#0b1e36]/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <!-- Main Content (Scroll Mandiri Vertikal) -->
        <main class="flex-1 w-full pl-0 md:pl-64 h-screen overflow-y-auto flex flex-col bg-[#f8fafc]">
            <header class="h-16 border-b border-slate-200/60 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between px-6 md:px-10 shrink-0">
                <div>
                    <button id="open-sidebar" class="p-2 -ml-2 text-slate-700 rounded-lg hover:bg-slate-100 md:hidden focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <div class="flex items-center gap-2.5 bg-slate-50 px-3.5 py-1.5 rounded-full border border-slate-200/60">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-semibold text-slate-600">Sesi Siswa Aktif</span>
                </div>
            </header>
            
            <div class="flex-1 p-6 md:p-10 max-w-[1400px]">
                {{ $slot }}
            </div>
        </main>

    </div>

    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('open-sidebar');
            const closeBtn = document.getElementById('close-sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });
            }

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            };

            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        });
    </script>
</body>
</html>