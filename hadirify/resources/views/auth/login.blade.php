<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk ke Sistem - Hadirify</title>
    
    <!-- Scripts & Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            background-color: #0b1c30; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.01em;
        }

        /* Sleek Corners (12px standard) */
        .card-pro {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-pro {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-pro:focus {
            background-color: #ffffff;
            border-color: #006196;
            box-shadow: 0 0 0 4px rgba(0, 97, 150, 0.1);
            outline: none;
        }

        .btn-pro {
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Glass decorative elements */
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
            opacity: 0.4;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-6">
    
    <!-- Background Glow -->
    <div class="bg-glow">
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-[#006196] blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-amber-600 blur-[120px] rounded-full"></div>
    </div>

    <main class="w-full max-w-[400px] animate-in fade-in zoom-in duration-700">
        
        <div class="card-pro overflow-hidden">
            <div class="p-8 sm:p-10 text-center">
                
                <!-- Branding -->
                <div class="flex flex-col items-center mb-8">
                    <div class="w-14 h-14 bg-[#0b1c30] rounded-2xl flex items-center justify-center shadow-xl mb-4 transform hover:rotate-6 transition-transform">
                        <i data-lucide="fingerprint" class="w-8 h-8 text-white"></i>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-none">Hadirify<span class="text-[#006196]">.</span></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Digital Attendance System</p>
                </div>

                <!-- Judul Akses -->
                <div class="mb-8 space-y-1">
                    <h2 class="text-lg font-bold text-slate-800">Masuk ke Akun</h2>
                    <p class="text-sm text-slate-500">Gunakan NISN, NUPTK, atau Email Anda.</p>
                </div>

                <!-- Alert Error -->
                @if($errors->any())
                    <div class="mb-6 p-3.5 bg-rose-50 border border-rose-100 text-rose-600 text-[11px] font-bold rounded-lg flex items-start gap-3 text-left">
                        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Form Login (Universal) -->
                <form method="POST" action="{{ route('login.proses') }}" class="space-y-5 text-left">
                    @csrf 
                    
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1" for="login_id">ID Pengguna / Email</label>
                        <div class="relative group">
                            <input name="login_id" id="login_id" 
                                   class="input-pro w-full h-12 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-300" 
                                   placeholder="Email / NISN / NUPTK" 
                                   type="text" required autofocus/>
                            <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-300 group-focus-within:text-[#006196] transition-colors"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest" for="password">Kata Sandi</label>
                            <a class="text-[11px] font-bold text-[#006196] hover:underline" href="#">Lupa?</a>
                        </div>
                        <div class="relative group">
                            <input name="password" id="password" 
                                   class="input-pro w-full h-12 pl-11 pr-4 text-sm text-slate-900 placeholder:text-slate-300" 
                                   placeholder="••••••••" 
                                   type="password" required/>
                            <i data-lucide="lock-keyhole" class="absolute left-4 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-300 group-focus-within:text-[#006196] transition-colors"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-pro w-full h-12 bg-[#0b1e36] hover:bg-black text-white text-[11px] font-black uppercase tracking-[0.15em] flex items-center justify-center gap-2 shadow-lg shadow-[#0b1e36]/20 active:scale-[0.98]">
                        Masuk Ke Sistem
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>
                
                <div class="mt-10 pt-6 border-t border-slate-50 text-center">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none">
                        SMA MUHAMMADIYAH 7 YOGYAKARTA
                    </p>
                    <p class="text-[8px] text-slate-300 mt-2 font-medium">© 2024 Hadirify Ecosystem &bull; v2.5.0</p>
                </div>

            </div>
        </div>
    </main>

    <script>
        // Render Icons
        lucide.createIcons();
    </script>
</body>
</html>