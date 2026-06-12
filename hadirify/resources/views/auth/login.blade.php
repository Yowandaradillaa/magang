@php
    $segment = request()->segment(1);
    
    // 1. DEFAULT: PORTAL SISWA
    $title = 'Portal Siswa';
    $subtitle = 'Silakan masukkan NISN dan password Anda untuk masuk.';
    $label = 'NISN (NOMOR INDUK SISWA NASIONAL)';
    $placeholder = 'Contoh: 0041234567';
    $btnTheme = 'bg-[#006196] hover:bg-[#004a75] shadow-primary/20';
    $iconTheme = 'text-[#006196]';
    $materialIcon = 'person'; 

    // 2. PORTAL GURU
    if ($segment === 'guru') {
        $title = 'Portal Guru';
        $subtitle = 'Silakan masukkan NUPTK/NIP dan password Anda.';
        $label = 'NUPTK / NIP PENDIDIK';
        $placeholder = 'Masukkan NUPTK atau NIP Anda...';
        $btnTheme = 'bg-amber-600 hover:bg-amber-700 shadow-amber-600/20';
        $iconTheme = 'text-amber-600';
        $materialIcon = 'school'; 
    } 
    // 3. PORTAL SUPER ADMIN
    elseif ($segment === 'admin') {
        $title = 'Portal Administrator';
        $subtitle = 'Akses khusus Tim TI & Manajemen Sekolah.';
        $label = 'ALAMAT EMAIL / NUPTK';
        $placeholder = 'admin@hadirify.sch.id';
        $btnTheme = 'bg-rose-600 hover:bg-rose-700 shadow-rose-600/20';
        $iconTheme = 'text-rose-600';
        $materialIcon = 'admin_panel_settings'; 
    }
@endphp

<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $title }} - Hadirify</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        theme: {
          extend: {
            "colors": {
                "outline-variant": "#bfc7d2",
                "primary": "#006196",
            },
            "fontFamily": {
                "body-md": ["Inter"],
                "display-lg": ["Inter"],
                "label-caps": ["Inter"],
                "button-text": ["Inter"]
            }
          }
        }
      }
    </script>
    <style>
      body {
        background-color: #0b1c30; 
        font-family: 'Inter', sans-serif;
      }
      .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
      
      @media (min-width: 768px) {
          .glass-card { 
              background: rgba(255, 255, 255, 1); 
              box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); 
          }
      }
      .input-glow:focus-within { box-shadow: 0 0 0 3px rgba(0, 99, 154, 0.1); }
    </style>
</head>

<body class="min-h-screen flex flex-col p-0 md:p-4 lg:p-8 relative z-0 overflow-x-hidden">
    
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none opacity-20 hidden md:block">
        <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] bg-[#006196] blur-[100px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-amber-600 blur-[100px] rounded-full"></div>
    </div>

    <main class="w-full min-h-screen md:min-h-0 md:max-w-[420px] mx-auto my-auto bg-white md:glass-card rounded-none md:rounded-[28px] overflow-hidden flex flex-col justify-center transition-all duration-500 md:hover:-translate-y-1">
        
        <div class="px-6 py-8 md:px-8 md:py-8 w-full">
            
            <div class="flex flex-col items-center mb-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-[#0b1c30] rounded-xl flex items-center justify-center shadow-lg transform rotate-[-2deg]">
                        <span class="material-symbols-outlined text-white text-2xl" data-icon="fingerprint" style="font-variation-settings: 'FILL' 1;">fingerprint</span>
                    </div>
                    <div>
                        <h1 class="font-display-lg text-[24px] font-bold text-[#0b1c30] tracking-tight">Hadirify</h1>
                        <p class="font-label-caps text-[9px] font-bold text-slate-500 tracking-widest uppercase">Sistem Absensi Digital</p>
                    </div>
                </div>
                <div class="inline-flex items-center bg-slate-50 border border-slate-200 px-3 py-1 rounded-full">
                    <span class="font-label-caps text-[9px] font-bold text-[#006196] text-center">SMA MUHAMMADIYAH 7 YOGYAKARTA</span>
                </div>
            </div>
            
            <div class="h-[1px] w-full bg-slate-200 mb-5"></div>
            
            <div class="mb-5">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined {{ $iconTheme }} text-[18px]" data-icon="{{ $materialIcon }}">{{ $materialIcon }}</span>
                    </div>
                    <h2 class="font-headline-md text-[16px] md:text-[18px] font-semibold text-[#0b1c30]">{{ $title }}</h2>
                </div>
                <p class="font-body-md text-[12px] md:text-[13px] text-slate-500">{{ $subtitle }}</p>
            </div>

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded-lg text-[12px] font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]" data-icon="error">error</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.proses') }}" class="space-y-3.5">
                @csrf 
                
                <div class="space-y-1">
                    <label class="font-label-caps text-[10px] md:text-[11px] font-bold text-slate-500 px-1" for="email">{{ $label }}</label>
                    <div class="relative group input-glow rounded-xl">
                        <input name="email" class="w-full h-11 bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-[13px] text-[#0b1c30] placeholder:text-slate-400 focus:outline-none focus:border-[#006196] transition-all duration-200" id="email" placeholder="{{ $placeholder }}" type="text" required autofocus/>
                        <span class="material-symbols-outlined text-[18px] absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#006196] transition-colors" data-icon="{{ $materialIcon }}">{{ $materialIcon }}</span>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="font-label-caps text-[10px] md:text-[11px] font-bold text-slate-500 px-1" for="password">KATA SANDI</label>
                    <div class="relative group input-glow rounded-xl">
                        <input name="password" class="w-full h-11 bg-white border border-slate-300 rounded-xl px-3.5 py-2 text-[13px] text-[#0b1c30] placeholder:text-slate-400 focus:outline-none focus:border-[#006196] transition-all duration-200" id="password" placeholder="••••••••" type="password" required/>
                        <span class="material-symbols-outlined text-[18px] absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#006196] transition-colors" data-icon="lock">lock</span>
                    </div>
                    <div class="text-right mt-1">
                        <a class="font-button-text text-[11px] font-bold text-[#006196] hover:underline transition-all" href="#">Lupa Password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full h-11 {{ $btnTheme }} text-white rounded-xl font-button-text text-[13px] font-bold flex items-center justify-center gap-1.5 transition-all duration-200 shadow-md mt-2">
                    Masuk ke Sistem
                    <span class="material-symbols-outlined text-[16px]" data-icon="arrow_forward">arrow_forward</span>
                </button>
            </form>
            
            <div class="mt-5 pt-4 border-t border-slate-200 text-center text-[11px] md:text-[12px] text-slate-500 font-medium">
                <span class="opacity-75">Bukan portal Anda?</span>
                <div class="mt-2 flex flex-wrap justify-center gap-2 md:gap-3">
                    @if($segment !== 'siswa' && $segment !== '')
                        <a href="/siswa/login" class="text-sky-600 font-bold hover:underline">Portal Siswa</a>
                    @endif
                    @if($segment !== 'guru')
                        @if($segment !== 'siswa' && $segment !== '') <span class="opacity-25 hidden md:inline">|</span> @endif
                        <a href="/guru/login" class="text-amber-600 font-bold hover:underline">Portal Guru</a>
                    @endif
                    @if($segment !== 'admin')
                        <span class="opacity-25 hidden md:inline">|</span>
                        <a href="/admin/login" class="text-rose-600 font-bold hover:underline">Portal Admin</a>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <script>
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('ring-2', 'ring-[#006196]/20');
            });
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('ring-2', 'ring-[#006196]/20');
            });
        });
    </script>
</body>
</html>