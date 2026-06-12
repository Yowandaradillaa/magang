<x-admin-layout>
    <!-- State Alpine.js: Tambah state untuk Modal Edit dan Data yang akan diedit -->
    <div x-data="{ 
        showModal: false, 
        showEditModal: false,
        editData: { id: '', nama_kelas: '', tahun_ajaran: '', id_wali_kelas: '' }
    }" class="animate-in fade-in duration-500 space-y-8 pb-20">
        
        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl font-bold text-sm flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= HEADER ================= -->
        <div class="bg-white p-8 rounded-3xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <span class="p-2.5 bg-rose-500/10 text-rose-600 rounded-2xl">
                        <i data-lucide="building" class="w-6 h-6"></i>
                    </span>
                    <h2 class="text-2xl font-black text-[#0b1e36] tracking-tight">Manajemen Kelas</h2>
                </div>
                <p class="text-sm text-slate-500 font-medium">Total: <span class="text-[#0b1e36] font-bold">{{ count($kelas) }} Kelas Terdaftar</span></p>
            </div>
            
            <button @click="showModal = true" class="flex items-center justify-center gap-2 px-7 py-3.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-sm font-bold rounded-2xl shadow-xl transition-all active:scale-95 cursor-pointer">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Tambah Kelas
            </button>
        </div>

        <!-- ================= TABEL DAFTAR KELAS ================= -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm overflow-hidden min-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        <th class="px-10 py-6">Nama Kelas</th>
                        <th class="px-10 py-6">Tahun Ajaran</th>
                        <th class="px-10 py-6">Wali Kelas</th>
                        <th class="px-10 py-6 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @forelse($kelas as $k)
                    <tr class="hover:bg-slate-50/40 transition-colors group">
                        <td class="px-10 py-6 font-black text-[#0b1e36] text-lg tracking-tight">{{ $k->nama_kelas }}</td>
                        <td class="px-10 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 font-mono text-xs rounded-lg border border-slate-200">{{ $k->tahun_ajaran }}</span>
                        </td>
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-[10px]">
                                    {{ strtoupper(substr($k->waliKelas->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-bold text-slate-700">{{ $k->waliKelas->name ?? 'Belum Ditentukan' }}</span>
                            </div>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- TOMBOL EDIT: Mengisi data ke Alpine.js state -->
                                <button @click="
                                    editData = { 
                                        id: '{{ $k->id }}', 
                                        nama_kelas: '{{ $k->nama_kelas }}', 
                                        tahun_ajaran: '{{ $k->tahun_ajaran }}', 
                                        id_wali_kelas: '{{ $k->id_wali_kelas }}' 
                                    }; 
                                    showEditModal = true" 
                                    class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-200 rounded-xl transition-all shadow-sm">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>

                                <!-- TOMBOL HAPUS -->
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-white border border-slate-100 text-slate-400 hover:text-rose-600 hover:border-rose-200 rounded-xl transition-all shadow-sm">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-32 text-center text-slate-300 font-black italic uppercase text-xs tracking-widest opacity-50">Belum ada data kelas terdaftar</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ================= MODAL TAMBAH ================= -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showModal" x-transition @click="showModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-10 md:p-12 overflow-hidden">
                    <h3 class="text-2xl font-black text-[#0b1e36] mb-6 tracking-tight">Tambah Kelas</h3>
                    <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kelas</label>
                            <input type="text" name="nama_kelas" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-rose-500 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Ajaran</label>
                                <select name="tahun_ajaran" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none cursor-pointer">
                                    <option value="">Pilih</option>
                                    @php $y = date('Y'); for($i=-1;$i<=2;$i++){ $v=($y+$i)."/".($y+$i+1); echo "<option value='$v'>$v</option>"; } @endphp
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Wali Kelas</label>
                                <select name="id_wali_kelas" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none cursor-pointer">
                                    <option value="">Guru</option>
                                    @foreach($gurus as $guru) <option value="{{ $guru->id }}">{{ $guru->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showModal = false" class="flex-1 py-4 text-slate-400 font-bold">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-rose-600 text-white font-bold rounded-2xl shadow-xl shadow-rose-200">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- ================= MODAL EDIT (DINAMIS) ================= -->
        <template x-teleport="body">
            <div x-show="showEditModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showEditModal" x-transition @click="showEditModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
                <div x-show="showEditModal" x-transition class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-10 md:p-12 overflow-hidden">
                    <h3 class="text-2xl font-black text-[#0b1e36] mb-6 tracking-tight">Edit Data Kelas</h3>
                    
                    <!-- Form Action Dinamis menggunakan ID dari Alpine.js -->
                    <form :action="'/admin/kelas/' + editData.id" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kelas</label>
                            <input type="text" name="nama_kelas" x-model="editData.nama_kelas" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm focus:border-blue-500 outline-none transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Ajaran</label>
                                <select name="tahun_ajaran" x-model="editData.tahun_ajaran" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none">
                                    @php $y = date('Y'); for($i=-1;$i<=2;$i++){ $v=($y+$i)."/".($y+$i+1); echo "<option value='$v'>$v</option>"; } @endphp
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Wali Kelas</label>
                                <select name="id_wali_kelas" x-model="editData.id_wali_kelas" required class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm outline-none appearance-none">
                                    @foreach($gurus as $guru) <option value="{{ $guru->id }}">{{ $guru->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showEditModal = false" class="flex-1 py-4 text-slate-400 font-bold">Batal</button>
                            <button type="submit" class="flex-[2] py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-200">Perbarui Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>

    <style> [x-cloak] { display: none !important; } </style>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-admin-layout>