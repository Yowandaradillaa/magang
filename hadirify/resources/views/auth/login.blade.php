@php
    $isGuru = request()->is('guru*');
    $isAdmin = request()->is('admin*');
    $isSiswa = !$isGuru && !$isAdmin;

    if ($isSiswa) {
        $title = 'Portal Siswa';
        $desc = 'Masukkan NISN dan password Anda.';
        $label = 'NISN';
        $placeholder = 'Contoh: 0041234567';
        $btnTheme = 'bg-[#00b4d8] hover:bg-[#009bc0] text-white';
    } elseif ($isGuru) {
        $title = 'Portal Guru';
        $desc = 'Masukkan NUPTK dan password Anda.';
        $label = 'NUPTK';
        $placeholder = 'Masukkan NUPTK...';
        $btnTheme = 'bg-[#0f4c75] hover:bg-[#1b6ca8] text-white';
    } else {
        $title = 'Portal Super Admin';
        $desc = 'Akses khusus Tim IT & Manajemen.';
        $label = 'Email / NUPTK';
        $placeholder = 'admin@sekolah.sch.id';
        $btnTheme = 'bg-amber-500 hover:bg-amber-600 text-white';
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Hadirify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#0f4c75] via-[#1b6ca8] to-[#00b4d8]">

    <div class="bg-white p-10 rounded-[24px] shadow-2xl w-[400px]">
        <div class="flex items-center justify-center gap-3 mb-8 pb-8 border-b border-[#e2e8f0]">
            <div class="w-10 h-10 bg-[#0f4c75] rounded-xl flex items-center justify-center text-white">
                <i data-lucide="clipboard-check" class="w-5 h-5" stroke-width="2.5"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-[#0f4c75] tracking-tight leading-none">Hadirify</h1>
                <p class="text-[10px] text-[#90a0b4] tracking-widest uppercase font-bold mt-1">Sistem Absensi</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 mb-2">
            @if($isSiswa) <div class="text-[#00b4d8]"><i data-lucide="user" class="w-7 h-7"></i></div>
            @elseif($isGuru) <div class="text-[#0f4c75]"><i data-lucide="graduation-cap" class="w-7 h-7"></i></div>
            @else <div class="text-amber-600"><i data-lucide="shield-check" class="w-7 h-7"></i></div>
            @endif
            <h2 class="text-[22px] font-extrabold text-[#1a2535]">{{ $title }}</h2>
        </div>
        <p class="text-[13px] text-[#5a6a80] mb-8">{{ $desc }}</p>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-[13px] font-semibold">
                <ul>@foreach ($errors->all() as $error) <li>⚠️ {{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[12px] font-bold text-[#5a6a80] uppercase tracking-wide mb-1.5">{{ $label }}</label>
                <input type="text" name="email" value="{{ old('email') }}" placeholder="{{ $placeholder }}" required autofocus class="w-full px-4 py-3 rounded-xl border-2 border-[#e2e8f0] focus:border-[#00b4d8] outline-none text-[14px] font-medium transition-colors"/>
            </div>
            <div>
                <label class="block text-[12px] font-bold text-[#5a6a80] uppercase tracking-wide mb-1.5">Password</label>
                <input type="password" name="password" placeholder="••••••••" required class="w-full px-4 py-3 rounded-xl border-2 border-[#e2e8f0] focus:border-[#00b4d8] outline-none text-[14px] font-medium transition-colors"/>
            </div>
            <div class="flex justify-end mb-6">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[12px] font-bold text-[#00b4d8] hover:underline">Lupa Password?</a>
                @endif
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold transition-all shadow-md hover:shadow-lg {{ $btnTheme }}">
                Masuk ke Sistem <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>