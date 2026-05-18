<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Hadirify</title>
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
            <div class="text-[#ef476f]"><i data-lucide="key-round" class="w-7 h-7"></i></div>
            <h2 class="text-[22px] font-extrabold text-[#1a2535]">Reset Password</h2>
        </div>
        
        <p class="text-[13px] text-[#5a6a80] mb-6 leading-relaxed">
            Lupa password Anda? Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.
        </p>

        @if (session('status'))
            <div class="mb-6 p-3 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-xl text-[13px] font-bold flex gap-2 items-center">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-[13px] font-semibold">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[12px] font-bold text-[#5a6a80] uppercase tracking-wide mb-1.5">
                    Alamat Email
                </label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="nama@sekolah.sch.id"
                    required 
                    autofocus
                    class="w-full px-4 py-3 rounded-xl border-2 border-[#e2e8f0] focus:border-[#ef476f] outline-none text-[14px] font-medium transition-colors"
                />
            </div>

            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl font-bold transition-all shadow-md hover:shadow-lg bg-[#ef476f] hover:bg-[#d83a5f] text-white"
            >
                Kirim Link Reset <i data-lucide="send" class="w-4 h-4"></i>
            </button>

            <div class="text-center mt-4 pt-4 border-t border-[#e2e8f0]">
                <a href="{{ route('login') }}" class="text-[13px] font-bold text-[#5a6a80] hover:text-[#0f4c75] transition-colors flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Login
                </a>
            </div>
        </form>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>