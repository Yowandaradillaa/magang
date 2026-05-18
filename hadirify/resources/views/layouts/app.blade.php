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

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-[#f7f9fc] antialiased text-[#1a2535]">

    <div class="min-h-screen flex">
        
        <aside class="fixed left-0 top-0 z-50 flex h-screen w-64 flex-col bg-[#0f4c75] text-white shadow-xl">
            
            <div class="flex items-center gap-3 border-b border-white/10 p-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-[#00b4d8]">
                    <i data-lucide="clipboard-check" class="w-6 h-6" stroke-width="2.5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold leading-tight tracking-tight">Hadirify</h2>
                    <p class="text-[10px] tracking-wider text-white/50 uppercase font-bold">Absensi Digital</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 bg-black/10 p-4 mx-3 my-4 rounded-xl border border-white/5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#00b4d8] to-[#06d6a0] font-bold text-white uppercase text-sm">
                    {{ substr(Auth::user()->name ?? 'SI', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <p class="truncate text-[13px] font-bold">{{ Auth::user()->name ?? 'Siswa Hadirify' }}</p>
                    <span class="mt-0.5 inline-block rounded-full bg-emerald-400/20 px-2 py-[2px] text-[9px] font-bold uppercase tracking-widest text-emerald-300">
                        {{ Auth::user()->role ?? 'siswa' }}
                    </span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 space-y-1">
                <p class="mb-2 mt-2 px-3 text-[10px] font-bold uppercase tracking-widest text-white/40">Menu Utama</p>
                
                @php $role = Auth::user()->role ?? 'siswa'; @endphp

                @if($role === 'siswa')
                    <a href="/siswa/dashboard" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all {{ request()->is('siswa/dashboard*') ? 'bg-white/10 font-semibold text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->is('siswa/dashboard*') ? 'text-[#00b4d8]' : '' }}"></i> 
                        <span class="text-[13.5px]">Dashboard</span>
                    </a>
                    
                    <a href="/siswa/scan-qr" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all {{ request()->is('siswa/scan-qr*') ? 'bg-white/10 font-semibold text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="camera" class="w-5 h-5 {{ request()->is('siswa/scan-qr*') ? 'text-[#00b4d8]' : '' }}"></i> 
                        <span class="text-[13.5px]">Scan QR Absen</span>
                    </a>

                    <a href="/siswa/rekap" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all {{ request()->is('siswa/rekap*') ? 'bg-white/10 font-semibold text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 {{ request()->is('siswa/rekap*') ? 'text-[#00b4d8]' : '' }}"></i> 
                        <span class="text-[13.5px]">Rekap Kehadiran</span>
                    </a>

                    <a href="/siswa/izin" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all {{ request()->is('siswa/izin*') ? 'bg-white/10 font-semibold text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <i data-lucide="file-text" class="w-5 h-5 {{ request()->is('siswa/izin*') ? 'text-[#00b4d8]' : '' }}"></i> 
                        <span class="text-[13.5px]">Ajukan Izin</span>
                    </a>

                    <a href="/siswa/notifikasi" class="flex items-center justify-between rounded-xl px-4 py-3 transition-all {{ request()->is('siswa/notifikasi*') ? 'bg-white/10 font-semibold text-white shadow-sm' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="bell" class="w-5 h-5 {{ request()->is('siswa/notifikasi*') ? 'text-[#00b4d8]' : '' }}"></i> 
                            <span class="text-[13.5px]">Notifikasi</span>
                        </div>
                        <span class="bg-[#ef476f] text-white text-[10px] font-black px-2 py-0.5 rounded-full shadow-sm animate-pulse">2</span>
                    </a>
                @endif

                @if($role === 'admin')
                    <a href="/admin/dashboard" class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all bg-white/10 font-semibold text-white shadow-sm">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-[#00b4d8]"></i> 
                        <span class="text-[13.5px]">Dashboard Admin</span>
                    </a>
                @endif
            </nav>

            <div class="border-t border-white/10 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-[#ef476f]/30 bg-[#ef476f]/10 p-2.5 text-[13px] font-bold text-[#ff8fa8] transition-colors hover:bg-[#ef476f]/20">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 pl-64 min-h-screen">
            <div class="p-8 max-w-[1400px] mx-auto">
                {{ $slot }}
            </div>
        </main>

    </div>

    <script>
        // Render semua icon Lucide otomatis
        lucide.createIcons();
    </script>
</body>
</html>