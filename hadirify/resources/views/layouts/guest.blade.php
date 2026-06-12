<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Portal Autentikasi - Hadirify SMA Muh 7</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script src="https://unpkg.com/lucide@latest"></script>
    </head>
    <body class="antialiased [font-family:'Plus_Jakarta_Sans',sans-serif] bg-gradient-to-br from-[#0b1e36] to-[#0f172a] min-h-screen relative overflow-hidden">
        
        <div class="absolute top-[-15%] left-[-10%] w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-15%] right-[-10%] w-[500px] h-[500px] bg-sky-500/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 relative z-10 px-4">
            
            <div class="text-center mb-8 animate-in fade-in slide-in-from-bottom-4 duration-700 ease-out">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-xl mb-5 transform transition-transform hover:scale-105">
                    <i data-lucide="clipboard-check" class="w-8 h-8" stroke-width="2.5"></i>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Hadirify</h1>
                <p class="text-[11px] font-black text-amber-400/90 uppercase tracking-[0.2em] mt-3 bg-amber-500/10 inline-block px-3 py-1 rounded-lg border border-amber-500/20">
                    SMA MUH 7 YOGYAKARTA
                </p>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl overflow-hidden rounded-[28px] border border-slate-100 animate-in fade-in slide-in-from-bottom-8 duration-700 ease-out delay-150">
                {{ $slot }}
            </div>
            
            <div class="mt-10 text-center text-[12px] font-semibold text-slate-400/50">
                &copy; {{ date('Y') }} Sistem Absensi Terpadu &bull; All Rights Reserved
            </div>
        </div>

        <script>
            // Render icon secara otomatis di halaman auth
            lucide.createIcons();
        </script>
    </body>
</html>