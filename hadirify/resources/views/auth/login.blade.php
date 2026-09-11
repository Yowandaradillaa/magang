<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk - Presensi SMA Muhammadiyah 7 Yogyakarta</title>
    
    <!-- Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js untuk interaksi Show/Hide Password -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6 bg-slate-900 relative overflow-hidden">

    <!-- Background dengan Efek Blur Estetik (Blob Cahaya) -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-[128px] opacity-60 animate-pulse"></div>
    <div class="absolute top-1/3 -right-32 w-96 h-96 bg-sky-400 rounded-full mix-blend-multiply filter blur-[128px] opacity-60"></div>
    <div class="absolute -bottom-32 left-1/4 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-60"></div>

    <main class="w-full max-w-[420px] relative z-10">
        
        <!-- Card Putih Bersih & Elegan -->
        <div class="bg-white rounded-3xl shadow-2xl border border-white/20 overflow-hidden p-8 sm:p-10 text-slate-800">
            
            <!-- Branding / Identitas Sekolah -->
            <div class="flex flex-col items-center text-center mb-8">
                <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-[#0b1e36] mb-3.5 border border-sky-100 shadow-sm">
                    <i data-lucide="school" class="w-6 h-6"></i>
                </div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Presensi Mutu</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">SMA Muhammadiyah 7 Yogyakarta</p>
            </div>

            <!-- Pesan Sambutan -->
            <div class="mb-6">
                <h2 class="text-base font-bold text-slate-800">Masuk ke Portal</h2>
                <p class="text-xs text-slate-500 mt-0.5">Silakan masukkan kredensial akun Anda.</p>
            </div>

            <!-- Alert Error -->
            @if($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200/80 text-rose-700 text-xs font-medium rounded-xl flex items-start gap-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5 text-rose-500"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('login.proses') }}" class="space-y-4 text-left">
                @csrf 
                
                <!-- Input ID / Email -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700 ml-0.5" for="login_id">ID Pengguna / Email</label>
                    <div class="relative">
                        <input name="login_id" id="login_id" 
                               class="w-full h-11 pl-10 pr-4 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0b1e36] focus:ring-2 focus:ring-[#0b1e36]/10 outline-none transition-all" 
                               placeholder="Email, NISN, atau NUPTK" 
                               type="text" required autofocus/>
                        <i data-lucide="user" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    </div>
                </div>

                <!-- Input Password dengan Toggle Mata (Alpine.js) -->
                <div class="space-y-1.5" x-data="{ showPassword: false }">
                    <div class="flex justify-between items-center ml-0.5">
                        <label class="text-xs font-semibold text-slate-700" for="password">Kata Sandi</label>
                        <a class="text-xs font-medium text-sky-600 hover:underline" href="#">Lupa sandi?</a>
                    </div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" id="password" 
                               class="w-full h-11 pl-10 pr-11 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0b1e36] focus:ring-2 focus:ring-[#0b1e36]/10 outline-none transition-all" 
                               placeholder="••••••••" 
                               required/>
                        <i data-lucide="lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        
                        <!-- Tombol Mata Interaktif -->
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                            <span x-show="!showPassword">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </span>
                            <span x-show="showPassword" style="display: none;">
                                <i data-lucide="eye-off" class="w-4 h-4"></i>
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full h-11 mt-2 bg-[#0b1e36] hover:bg-slate-900 text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-md transition-all active:scale-[0.98]">
                    <span>Masuk ke Sistem</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
            
            <!-- Footer Kecil -->
            <div class="mt-8 pt-5 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400 font-medium">
                    &copy; {{ date('Y') }} SMA Muhammadiyah 7 Yogyakarta
                </p>
            </div>

        </div>
    </main>

    <script>
        // Render Icons Lucide
        lucide.createIcons();
    </script>
</body>
</html>