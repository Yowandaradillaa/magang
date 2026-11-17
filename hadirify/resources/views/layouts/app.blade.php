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

<!-- Tambahkan x-data untuk mengontrol modal di tag body -->
<body class="bg-[#f8fafc] text-[#0b1e36] antialiased overflow-hidden" x-data="{ logoutModal: false }">

    <div class="h-screen w-full flex overflow-hidden">
        
        <!-- Sidebar Siswa (Fixed / Sticky Screen Height) -->
        <aside id="sidebar" class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col bg-[#0b1e36] text-white p-4 shadow-2xl border-r border-white/10 transition-transform duration-300 transform -translate-x-full md:translate-x-0 justify-between">
            
            <div class="flex flex-col min-h-0 space-y-4 flex-1 overflow-hidden">
                
                <!-- Logo & Portal Title (Clean Minimalist Header) -->
                <div class="px-2 pt-2 relative shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-sky-500 text-white rounded-lg flex items-center justify-center shrink-0 shadow-sm shadow-sky-500/30">
                            <i data-lucide="school" class="w-5 h-5" stroke-width="2"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold leading-tight tracking-tight text-white uppercase">Presensi Mutu</h2>
                            <p class="text-[11px] text-slate-400 font-medium truncate">SMA Muhammadiyah 7</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu (Gaya Bersih & Minimalis Solid Blue seperti Referensi) -->
                <nav class="flex-1 overflow-y-auto pr-1 space-y-1.5 custom-scrollbar pt-2">
                    <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>
                    
                    @php $role = Auth::user()->role ?? 'siswa'; @endphp

                    @if($role === 'siswa')
                        <a href="/siswa/dashboard" class="group flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-xs transition-all duration-150 {{ request()->is('siswa/dashboard*') ? 'bg-sky-500 font-bold text-white shadow-md shadow-sky-500/20' : 'text-slate-400 font-medium hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0 {{ request()->is('siswa/dashboard*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" stroke-width="2"></i> 
                            <span>Dashboard</span>
                        </a>
                        
                        <a href="/siswa/scan-qr" class="group flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-xs transition-all duration-150 {{ request()->is('siswa/scan-qr*') ? 'bg-sky-500 font-bold text-white shadow-md shadow-sky-500/20' : 'text-slate-400 font-medium hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="qr-code" class="w-[18px] h-[18px] shrink-0 {{ request()->is('siswa/scan-qr*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" stroke-width="2"></i> 
                            <span>Scan QR Absen</span>
                        </a>

                        <a href="/siswa/rekap" class="group flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-xs transition-all duration-150 {{ request()->is('siswa/rekap*') ? 'bg-sky-500 font-bold text-white shadow-md shadow-sky-500/20' : 'text-slate-400 font-medium hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="history" class="w-[18px] h-[18px] shrink-0 {{ request()->is('siswa/rekap*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" stroke-width="2"></i> 
                            <span>Rekap Kehadiran</span>
                        </a>

                        <a href="/siswa/izin" class="group flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-xs transition-all duration-150 {{ request()->is('siswa/izin*') ? 'bg-sky-500 font-bold text-white shadow-md shadow-sky-500/20' : 'text-slate-400 font-medium hover:bg-white/5 hover:text-white' }}">
                            <i data-lucide="file-signature" class="w-[18px] h-[18px] shrink-0 {{ request()->is('siswa/izin*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" stroke-width="2"></i> 
                            <span>Ajukan Izin</span>
                        </a>

                        <a href="/siswa/notifikasi" class="group flex items-center justify-between rounded-lg px-3.5 py-2.5 text-xs transition-all duration-150 {{ request()->is('siswa/notifikasi*') ? 'bg-sky-500 font-bold text-white shadow-md shadow-sky-500/20' : 'text-slate-400 font-medium hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="bell" class="w-[18px] h-[18px] shrink-0 {{ request()->is('siswa/notifikasi*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" stroke-width="2"></i> 
                                <span>Notifikasi</span>
                            </div>
                            @php
                                $user = Auth::user();
                                $notifCount = \App\Models\Pengumuman::where('kelas_id', $user->id_kelas ?? null)
                                    ->where('created_at', '>', $user->notification_last_viewed_at ?? '2000-01-01')
                                    ->count();
                            @endphp

                            @if($notifCount > 0)
                                <span class="{{ request()->is('siswa/notifikasi*') ? 'bg-white text-sky-600' : 'bg-sky-500 text-white' }} text-[10px] font-bold px-2 py-0.5 rounded-md">
                                    {{ $notifCount }}
                                </span>
                            @endif
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Profil & Logout Section -->
            <div class="border-t border-white/10 pt-3 space-y-2 shrink-0 mt-3">
                <div class="flex items-center gap-2.5 bg-white/5 p-2.5 rounded-lg border border-white/10">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-sky-500 font-extrabold text-white text-xs shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'SW', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name ?? 'Siswa' }}</p>
                        <span class="block text-[10px] font-medium text-slate-400 truncate">
                            Kelas {{ Auth::user()->kelas->nama_kelas ?? 'Siswa Aktif' }}
                        </span>
                    </div>
                </div>

                <button @click="logoutModal = true" type="button" class="group flex w-full items-center justify-center gap-2 rounded-lg border border-white/10 bg-white/5 py-2 text-xs font-bold text-slate-300 transition-colors hover:bg-rose-500/20 hover:border-rose-400/30 hover:text-rose-200 cursor-pointer">
                    <i data-lucide="log-out" class="w-4 h-4" stroke-width="1.9"></i> Keluar
                </button>
            </div>
        </aside>

        <div id="sidebar-overlay" class="fixed inset-0 bg-[#0b1e36]/60 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>

        <!-- Main Content (Scroll Mandiri Vertikal) -->
        <main class="flex-1 min-w-0 w-full pl-0 md:pl-64 h-screen overflow-y-auto flex flex-col bg-[#f8fafc]">
            <header class="h-14 border-b border-slate-200/70 bg-white/90 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between px-4 md:px-6 shrink-0">
                <div class="flex items-center gap-3">
                    <button id="open-sidebar" class="p-2 text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 md:hidden focus:outline-none">
                        <i data-lucide="menu" class="w-[18px] h-[18px]"></i>
                    </button>
                    <span class="hidden sm:inline-block text-xs font-semibold text-slate-500">Presensi Mutu • Portal Siswa</span>
                </div>
                
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-[11px] font-bold text-emerald-700">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sesi Siswa Aktif</span>
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
                <p class="text-xs text-slate-500">Apakah Anda yakin ingin mengakhiri sesi dan keluar dari Portal Siswa?</p>
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