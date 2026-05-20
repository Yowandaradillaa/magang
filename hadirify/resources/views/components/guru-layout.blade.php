<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hadirify Guru - Sistem Presensi Digital</title>
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
        <aside class="w-[280px] bg-[#0f4c75] text-white p-6 flex flex-col justify-between shrink-0">
            <div class="space-y-8">
                <div class="flex items-center gap-3 px-2">
                    <div class="p-2.5 bg-white/10 rounded-xl">
                        <i data-lucide="qr-code" class="w-6 h-6 text-[#00b4d8]" stroke-width="2.5"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-black tracking-tight leading-none">Hadirify</h2>
                        <span class="text-[10px] font-bold text-[#00b4d8] tracking-widest uppercase">Portal Guru</span>
                    </div>
                </div>

                <nav class="space-y-1.5">
    
                    <a href="/guru/dashboard" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl font-bold text-[13.5px] bg-white/10 text-[#00b4d8] transition-all">
                        <i data-lucide="layout-dashboard" class="w-4.5 h-4.5"></i> Dashboard
                    </a>
                    
                    <a href="/guru/qr" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl font-bold text-[13.5px] text-white/70 hover:bg-white/5 hover:text-white transition-all">
                        <i data-lucide="qr-code" class="w-4.5 h-4.5"></i> QR Absensi
                    </a>
                    
                    <a href="/guru/manual" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl font-bold text-[13.5px] text-white/70 hover:bg-white/5 hover:text-white transition-all">
                        <i data-lucide="edit" class="w-4.5 h-4.5"></i> Input Manual
                    </a>
                    
                    <a href="/guru/izin" class="flex items-center justify-between px-4 py-3.5 rounded-xl font-bold text-[13.5px] text-white/70 hover:bg-white/5 hover:text-white transition-all">
                        <div class="flex items-center gap-3.5">
                            <i data-lucide="clipboard-list" class="w-4.5 h-4.5"></i> Izin Siswa
                        </div>
                        <span class="bg-[#ef476f] text-white text-[10px] px-2 py-0.5 rounded-full font-black">3</span>
                    </a>
                    
                    <a href="/guru/rekap" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl font-bold text-[13.5px] text-white/70 hover:bg-white/5 hover:text-white transition-all">
                        <i data-lucide="bar-chart-2" class="w-4.5 h-4.5"></i> Rekap & Export
                    </a>
                    
                    <a href="/guru/pengumuman" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl font-bold text-[13.5px] text-white/70 hover:bg-white/5 hover:text-white transition-all">
                        <i data-lucide="megaphone" class="w-4.5 h-4.5"></i> Pengumuman
                    </a>

                </nav>
            <div class="border-t border-white/10 pt-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#00b4d8] flex items-center justify-center font-black text-white text-sm">
                        GR
                    </div>
                    <div>
                        <h4 class="text-[13px] font-black leading-tight">Budi Santoso, M.Pd</h4>
                        <span class="text-[11px] font-medium text-white/50">NIP. 19820311...</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 text-white/40 hover:text-rose-400 rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-10 overflow-y-auto max-w-[1400px]">
            {{ $slot }}
        </main>
    </div>

    <script>
        // Inisialisasi ikon Lucide secara global
        lucide.createIcons();
    </script>
</body>
</html>