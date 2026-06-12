<x-admin-layout>
    <!-- State Utama: Tab, Modal, dan Role Pilihan di Form -->
    <div x-data="{ tab: 'siswa', showModal: false, rolePilihan: 'siswa' }" class="animate-in fade-in duration-500 space-y-8 pb-20">
        
        <!-- Pesan Sukses (Muncul setelah simpan data) -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <!-- ================= HEADER ================= -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 bg-rose-500/10 text-rose-600 rounded-2xl">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </span>
                    <h2 class="text-2xl font-black text-[#0b1e36]">Kelola Akun</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Database kredensial akun Siswa dan Guru.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <button type="button" @click="showModal = true" 
                        class="flex items-center gap-2.5 px-7 py-3.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-sm font-bold rounded-2xl shadow-xl transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    Tambah Akun Baru
                </button>
            </div>
        </div>

        <!-- ================= MODAL POP-UP (TAMBAH AKUN) ================= -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <!-- Backdrop -->
                <div x-show="showModal" x-transition @click="showModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                <!-- Konten Modal -->
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-xl rounded-3xl shadow-2xl p-10 overflow-y-auto max-h-[90vh]">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-2xl font-black text-[#0b1e36]">Tambah Akun</h3>
                        <button @click="showModal = false" class="p-2 text-slate-400 hover:text-rose-500 transition-colors"><i data-lucide="x" class="w-6 h-6"></i></button>
                    </div>

                    <!-- Tampilkan Error Validasi (PENTING: Biar tahu kenapa data ga masuk) -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs rounded-xl">
                            <p class="font-bold mb-1 uppercase">Gagal Menyimpan:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.akun.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <!-- Nama Lengkap -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama..." 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-rose-500 outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-5">
                            <!-- Role Dropdown -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Role / Jabatan</label>
                                <select name="role" x-model="rolePilihan" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                            <!-- Kelas (Hanya muncul jika Siswa) -->
                            <div class="space-y-1.5" x-show="rolePilihan === 'siswa'">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelas</label>
                                <select name="id_kelas" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5">
                            <!-- NISN (Hanya Siswa) -->
                            <div class="space-y-1.5" x-show="rolePilihan === 'siswa'">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Induk Siswa (NISN)</label>
                                <input type="text" name="nisn" :required="rolePilihan === 'siswa'" placeholder="10 Digit NISN"
                                       class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-rose-500 outline-none transition-all">
                            </div>
                            <!-- NUPTK (Hanya Guru) -->
                            <div class="space-y-1.5" x-show="rolePilihan === 'guru'">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Induk Guru (NUPTK)</label>
                                <input type="text" name="nuptk" :required="rolePilihan === 'guru'" placeholder="Masukkan NUPTK"
                                       class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-rose-500 outline-none transition-all">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Sekolah (Opsional)</label>
                            <input type="email" name="email" placeholder="nama@sekolah.id" 
                                   class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-rose-500 outline-none transition-all">
                        </div>

                        <div class="flex gap-4 pt-6">
                            <button type="button" @click="showModal = false" class="flex-1 py-4 bg-slate-50 text-slate-400 font-bold rounded-2xl hover:bg-slate-100 transition-all">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-xl shadow-rose-200 transition-all flex items-center justify-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i> Simpan Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- ================= NAVIGASI TAB ================= -->
        <div class="flex items-center gap-2 bg-slate-100/50 p-2 rounded-2xl w-fit border border-slate-200/60 shadow-inner">
            <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'bg-white text-rose-600 shadow-sm font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="px-8 py-3 rounded-xl text-sm transition-all focus:outline-none flex items-center gap-2">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i> Data Siswa
            </button>
            <button @click="tab = 'guru'" :class="tab === 'guru' ? 'bg-white text-rose-600 shadow-sm font-bold' : 'text-slate-400 hover:text-slate-600'"
                    class="px-8 py-3 rounded-xl text-sm transition-all focus:outline-none flex items-center gap-2">
                <i data-lucide="briefcase" class="w-4 h-4"></i> Data Guru
            </button>
        </div>

        <!-- ================= TABEL DATA ================= -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm overflow-hidden min-h-[500px]">
            
            <!-- Tab Konten Siswa -->
            <div x-show="tab === 'siswa'" class="animate-in fade-in duration-500">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-10 py-6">Informasi Siswa</th>
                            <th class="px-10 py-6">Kelas & Identitas</th>
                            <th class="px-10 py-6 text-center">Status</th>
                            <th class="px-10 py-6 text-right">Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($users->where('role', 'siswa') as $siswa)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-black text-sm shadow-inner">
                                        {{ strtoupper(substr($siswa->name, 0, 2)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0b1e36] text-base">{{ $siswa->name }}</span>
                                        <span class="text-xs text-slate-400 italic">{{ $siswa->email ?? 'no-email@sch.id' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-600 uppercase">{{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono italic tracking-tighter">NISN: {{ $siswa->nisn ?? '---' }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-center">
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100 uppercase">Aktif</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-[#0b1e36] rounded-xl shadow-sm transition-all"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                    
                                    <form action="{{ route('admin.akun.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Hapus akun siswa ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-rose-600 rounded-xl shadow-sm transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-40 text-center text-slate-300 font-bold uppercase text-xs italic tracking-widest">Belum ada data siswa terdaftar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tab Konten Guru -->
            <div x-show="tab === 'guru'" style="display: none;" class="animate-in fade-in duration-500">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                            <th class="px-10 py-6">Detail Pendidik</th>
                            <th class="px-10 py-6">NUPTK</th>
                            <th class="px-10 py-6 text-center">Akses</th>
                            <th class="px-10 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($users->where('role', 'guru') as $guru)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-sm shadow-inner">
                                        {{ strtoupper(substr($guru->name, 0, 2)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#0b1e36] text-base">{{ $guru->name }}</span>
                                        <span class="text-xs text-slate-400 italic">{{ $guru->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-6 text-slate-600 font-mono font-bold">{{ $guru->nuptk ?? 'NUPTK_KOSONG' }}</td>
                            <td class="px-10 py-6 text-center">
                                <span class="px-4 py-1 bg-blue-50 text-blue-600 text-[10px] font-black rounded-full border border-blue-100 uppercase">Teacher</span>
                            </td>
                            <td class="px-10 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 rounded-xl shadow-sm transition-all"><i data-lucide="edit" class="w-4 h-4"></i></button>
                                    
                                    <form action="{{ route('admin.akun.destroy', $guru->id) }}" method="POST" onsubmit="return confirm('Hapus akun guru ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-rose-600 rounded-xl shadow-sm transition-all"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-40 text-center text-slate-300 font-bold uppercase text-xs italic tracking-widest">Belum ada data guru terdaftar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Script & Style Tambahan -->
    <style> [x-cloak] { display: none !important; } </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin-layout>