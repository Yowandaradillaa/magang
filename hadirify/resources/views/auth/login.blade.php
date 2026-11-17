<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk - Presensi Mutu</title>
    
    <!-- Scripts & Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Alpine.js untuk interaksi Show/Hide Password -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-white font-sans antialiased text-slate-800 selection:bg-sky-500 selection:text-white">

    <!-- Container Utama Split-Screen (Full Height & Full Width) -->
    <div class="min-h-screen md:h-screen w-full flex flex-col md:flex-row overflow-x-hidden md:overflow-hidden">
        
        <!-- ================= SISI KIRI: BRANDING PANEL (50% pada Desktop, Hidden di Mobile) ================= -->
        <div class="hidden md:flex md:w-1/2 relative flex-col justify-between p-10 lg:p-14 text-white overflow-hidden bg-gradient-to-br from-[#071322] via-[#0b1e36] to-[#15345a]">
            
            <!-- Ornamen Desain Blob Cahaya Asimetris (CSS Murni) -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-sky-500/20 rounded-full filter blur-[100px] pointer-events-none animate-pulse"></div>
            <div class="absolute -bottom-28 -right-28 w-96 h-96 bg-blue-600/25 rounded-full filter blur-[110px] pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-500/15 rounded-full filter blur-[120px] pointer-events-none"></div>
            
            <!-- Grid Pattern Overlay Halus -->
            <div class="absolute inset-0 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>

            <!-- Header Kecil Branding Atas -->
            <div class="relative z-10 flex items-center gap-3">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[11px] font-bold tracking-wider uppercase text-sky-200">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                    Portal Presensi Digital
                </span>
            </div>

            <!-- Konten Utama Tengah (Logo + Heading + Deskripsi) -->
            <div class="relative z-10 flex flex-col items-center text-center my-auto px-4 py-8">
                <!-- Logo Sekolah dengan Backdrop Cahaya & Efek Interaktif -->
                <div class="relative group mb-6">
                    <div class="absolute -inset-4 bg-sky-400/20 rounded-full blur-2xl opacity-60 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img src="{{ asset('images/logo-mutu.png') }}?v={{ file_exists(public_path('images/logo-mutu.png')) ? filemtime(public_path('images/logo-mutu.png')) : time() }}" 
                         alt="Logo SMA Mutu" 
                         class="relative w-36 h-36 lg:w-44 lg:h-44 object-contain drop-shadow-[0_15px_25px_rgba(0,0,0,0.4)] transition-transform duration-500 group-hover:scale-105">
                </div>

                <!-- Teks Heading & Sub-teks Kontras -->
                <h1 class="text-2xl lg:text-3xl xl:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm leading-tight">
                     Presensi Mutu
                </h1>
                <p class="text-sm lg:text-base text-sky-100/80 font-medium max-w-md leading-relaxed mt-3">
                    Sistem Informasi Presensi Terpadu SMA Muhammadiyah 7 Yogyakarta
                </p>

                <!-- Fitur Highlight Pill Badges -->
                <div class="flex flex-wrap items-center justify-center gap-2 mt-7">
                    <div class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-xl text-xs font-semibold text-sky-200 flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="qr-code" class="w-3.5 h-3.5 text-sky-300"></i>
                        <span>Presensi QR Cepat</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-xl text-xs font-semibold text-sky-200 flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="bar-chart-2" class="w-3.5 h-3.5 text-sky-300"></i>
                        <span>Rekapitulasi Real-Time</span>
                    </div>
                    <div class="px-3.5 py-1.5 bg-white/10 backdrop-blur-md border border-white/15 rounded-xl text-xs font-semibold text-sky-200 flex items-center gap-1.5 shadow-sm">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-sky-300"></i>
                        <span>Aman & Terintegrasi</span>
                    </div>
                </div>
            </div>

            <!-- Footer Panel Kiri -->
            <div class="relative z-10 pt-4 border-t border-white/10 text-xs text-slate-400 font-medium flex items-center justify-between">
                <span>Versi 2.0 • SMA Mutu</span>
                <span class="text-sky-300/80">Yogyakarta, Indonesia</span>
            </div>
        </div>

        <!-- ================= SISI KANAN: FORM PANEL (100% Mobile, 50% Desktop) ================= -->
        <div class="w-full md:w-1/2 flex flex-col justify-between bg-white min-h-screen md:min-h-0 overflow-y-auto">
            
            <!-- Header Mobile Baru (Bar Biru di Atas) -->
            <div class="md:hidden bg-[#0b1e36] px-5 py-4 flex items-center gap-3 shadow-md border-b border-blue-900">
                <img src="{{ asset('images/logo-mutu.png') }}?v={{ file_exists(public_path('images/logo-mutu.png')) ? filemtime(public_path('images/logo-mutu.png')) : time() }}" 
                     alt="Logo SMA Mutu" 
                     class="w-10 h-10 object-contain drop-shadow">
                <div>
                    <h2 class="text-white text-base font-extrabold tracking-tight leading-tight">Presensi Mutu</h2>
                    <p class="text-blue-200 text-xs">SMA Muhammadiyah 7 Yogyakarta</p>
                </div>
            </div>

            <!-- Konten Mobile di Bawah Bar Biru (Form Panel) -->
            <div class="flex-1 flex flex-col justify-between px-6 py-6 sm:p-10 lg:p-16">
                
                <!-- Form Wrapper Terpusat di Tengah Layar -->
                <div class="w-full max-w-md mx-auto my-auto py-4 sm:py-8">
                
                <!-- Judul & Pengantar Form -->
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-[11px] font-bold tracking-wide uppercase mb-3 border border-sky-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Selamat Datang
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0b1e36] tracking-tight">Masuk ke Portal</h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1.5 font-medium leading-relaxed">
                        Silakan masukkan kredensial akun Anda untuk mengakses sistem presensi.
                    </p>
                </div>

                <!-- Notifikasi Error Validasi / Login Gagal -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium rounded-xl flex items-start gap-3 shadow-sm animate-in fade-in">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5 text-rose-500"></i>
                        <span class="leading-relaxed">{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium rounded-xl flex items-start gap-3 shadow-sm animate-in fade-in">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5 text-rose-500"></i>
                        <span class="leading-relaxed">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.proses') }}" class="space-y-4">
                    @csrf 

                    <!-- Input ID Pengguna / Email -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 ml-0.5" for="login_id">
                            ID Pengguna / Email
                        </label>
                        <div class="relative group">
                            <input name="login_id" id="login_id" 
                                   value="{{ old('login_id') }}"
                                   class="w-full h-12 pl-11 pr-4 text-sm bg-slate-50/80 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0b1e36] focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 outline-none transition-all duration-200" 
                                   placeholder="Email, NISN, atau NUPTK" 
                                   type="text" required autofocus/>
                            <i data-lucide="user" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400 group-focus-within:text-[#0b1e36] transition-colors"></i>
                        </div>
                    </div>

                    <!-- Input Password dengan Toggle Mata (Alpine.js) -->
                    <div class="space-y-1.5" x-data="{ showPassword: false }">
                        <div class="flex justify-between items-center ml-0.5">
                            <label class="text-xs font-bold text-slate-700" for="password">Kata Sandi</label>
                            <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors" href="#">Lupa sandi?</a>
                        </div>
                        <div class="relative group">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   name="password" id="password" 
                                   class="w-full h-12 pl-11 pr-11 text-sm bg-slate-50/80 border border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-[#0b1e36] focus:ring-4 focus:ring-blue-500/10 hover:border-slate-300 outline-none transition-all duration-200" 
                                   placeholder="••••••••" 
                                   required/>
                            <i data-lucide="lock" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400 group-focus-within:text-[#0b1e36] transition-colors"></i>
                            
                            <!-- Tombol Mata Interaktif -->
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 focus:outline-none cursor-pointer p-1 rounded-md transition-colors"
                                    title="Tampilkan / Sembunyikan Sandi">
                                <span x-show="!showPassword">
                                    <i data-lucide="eye" class="w-4.5 h-4.5"></i>
                                </span>
                                <span x-show="showPassword" style="display: none;">
                                    <i data-lucide="eye-off" class="w-4.5 h-4.5"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Masuk Sistem -->
                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full h-12 bg-[#0b1e36] hover:bg-[#112a4a] text-white text-xs font-bold uppercase tracking-wider rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-[#0b1e36]/20 transition-all duration-200 active:scale-[0.98] cursor-pointer">
                            <span>Masuk ke Sistem</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 stroke-[2.5]"></i>
                        </button>
                    </div>
                </form>
            </div>

                <!-- Footer Panel Form -->
                <div class="mt-auto border-t border-slate-100">
                    <p class="text-[11px] text-slate-400 text-center pb-6 pt-4">
                        &copy; 2026 SMA Muhammadiyah 7 Yogyakarta
                    </p>
                </div>

            </div>
        </div>

    </div>

    <script>
        // Inisialisasi ikon Lucide
        lucide.createIcons();
    </script>
</body>
</html>