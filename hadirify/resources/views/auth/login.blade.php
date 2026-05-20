@php
    $segment = request()->segment(1);
    
    // 1. DEFAULT: PORTAL SISWA
    $title = 'Portal Siswa';
    $subtitle = 'Masukkan NISN dan password Anda.';
    $label = 'NISN';
    $placeholder = 'Contoh: 0041234567';
    $colorTheme = 'text-[#00b4d8]';
    $btnTheme = 'bg-[#00b4d8] hover:bg-[#009bc0]';
    // Icon User (Siswa)
    $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';

    // 2. PORTAL GURU
    if ($segment === 'guru') {
        $title = 'Portal Guru';
        $subtitle = 'Masukkan NUPTK dan password Anda.';
        $label = 'NUPTK';
        $placeholder = 'Masukkan NUPTK...';
        $colorTheme = 'text-[#0f4c75]';
        $btnTheme = 'bg-[#0f4c75] hover:bg-[#1b6ca8]';
        // Icon Toga (Guru)
        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>';
    } 
    // 3. PORTAL SUPER ADMIN
    elseif ($segment === 'admin') {
        $title = 'Portal Super Admin';
        $subtitle = 'Akses khusus Tim IT & Manajemen.';
        $label = 'EMAIL / NUPTK';
        $placeholder = 'admin@sekolah.sch.id';
        $colorTheme = 'text-[#f59e0b]'; // Warna Kuning/Orange Admin
        $btnTheme = 'bg-[#f59e0b] hover:bg-[#d97706]';
        // Icon Shield Check (Admin)
        $iconSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="m9 12 2 2 4-4"></path></svg>';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Hadirify</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-[#f7f9fc]" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <div class="absolute inset-0 bg-[#0f4c75]"></div>
    
    <div class="bg-white rounded-[24px] p-10 w-full max-w-[420px] shadow-xl relative z-10">
        
        <div class="flex items-center justify-center gap-3.5 mb-7">
            <div class="w-12 h-12 bg-[#0f4c75] rounded-[14px] flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="m9 14 2 2 4-4"></path></svg>
            </div>
            <div>
                <h1 class="text-[22px] font-black text-[#0f4c75] leading-none tracking-tight">Hadirify</h1>
                <p class="text-[10px] font-bold text-[#90a0b4] uppercase tracking-[1.5px] mt-1">Sistem Absensi</p>
            </div>
        </div>

        <hr class="border-[#e2e8f0] mb-8">

        <div class="mb-8">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="{{ $colorTheme }}">
                    {!! $iconSvg !!}
                </div>
                <h2 class="text-[22px] font-extrabold text-[#0f4c75] tracking-tight">{{ $title }}</h2>
            </div>
            <p class="text-[14px] text-[#5a6a80] font-medium">{{ $subtitle }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-600 text-[13px] font-bold rounded-xl">
                Kredensial yang Anda masukkan salah.
            </div>
        @endif

        <form method="POST" action="{{ route('login.proses') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-[12px] font-bold text-[#5a6a80] mb-2">{{ $label }}</label>
                <input type="text" name="email" id="email" required autofocus placeholder="{{ $placeholder }}"
                       class="w-full px-4 py-3.5 rounded-xl border border-[#e2e8f0] focus:border-[#00b4d8] focus:ring-2 focus:ring-[#00b4d8]/20 outline-none text-[14.5px] font-medium text-[#1a2535] placeholder-[#90a0b4] transition-all">
            </div>

            <div>
                <label for="password" class="block text-[12px] font-bold text-[#5a6a80] mb-2">PASSWORD</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                       class="w-full px-4 py-3.5 rounded-xl border border-[#e2e8f0] focus:border-[#00b4d8] focus:ring-2 focus:ring-[#00b4d8]/20 outline-none text-[14.5px] font-medium text-[#1a2535] placeholder-[#90a0b4] transition-all">
            </div>

            <div class="flex justify-end pt-1">
                <a href="#" class="text-[12px] font-extrabold text-[#00b4d8] hover:underline">Lupa Password?</a>
            </div>

            <button type="submit" class="w-full py-3.5 {{ $btnTheme }} text-white rounded-xl text-[15px] font-bold shadow-sm transition-all flex items-center justify-center gap-2 mt-2">
                Masuk ke Sistem
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </button>
        </form>

    </div>

</body>
</html>