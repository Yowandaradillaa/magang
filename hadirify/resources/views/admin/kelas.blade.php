<x-admin-layout>
    <!-- State Alpine.js: Mengontrol Tab, Modal Tambah, Edit, dan Hapus -->
    <div x-data="{ 
        showModal: false, 
        showEditModal: false,
        showDeleteModal: false,
        deleteUrl: '',
        editData: { id: '', nama_kelas: '', tahun_ajaran: '', id_wali_kelas: '' }
    }" class="animate-in fade-in duration-500 flex flex-col space-y-4 px-2 h-[calc(100vh-140px)]">
        
        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white p-5 rounded-xl border border-slate-200/50 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#0b1e36] text-white rounded-lg flex items-center justify-center shadow-lg">
                    <i data-lucide="building" class="w-5 h-5"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Manajemen Kelas</h2>
                    <p class="text-[11px] text-slate-500 font-medium">
                        Struktur Kurikulum • <span class="text-[#0b1e36] font-bold">{{ count($kelas) }} Kelas Terdaftar</span>
                    </p>
                </div>
            </div>
            
            <button @click="showModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b1e36] hover:bg-slate-800 text-white text-[11px] font-bold rounded-lg shadow-lg transition-all active:scale-95 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Kelas Baru
            </button>
        </div>

        <!-- ================= SECTION 2: TABEL (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-y-auto no-scrollbar relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white z-10 border-b border-slate-100">
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Identitas Kelas</th>
                            <th class="px-6 py-4">Tahun Ajaran</th>
                            <th class="px-6 py-4">Wali Kelas</th>
                            <th class="px-6 py-4 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($kelas as $k)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3 font-bold text-slate-800 text-xs">{{ $k->nama_kelas }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-mono text-[10px] font-bold rounded border border-slate-200">
                                    {{ $k->tahun_ajaran }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                <span class="font-bold text-slate-600 text-xs">{{ $k->waliKelas->name ?? '---' }}</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <!-- Tombol Edit -->
                                    <button @click="
                                        editData = { 
                                            id: '{{ $k->id }}', 
                                            nama_kelas: '{{ $k->nama_kelas }}', 
                                            tahun_ajaran: '{{ $k->tahun_ajaran }}', 
                                            id_wali_kelas: '{{ $k->id_wali_kelas }}' 
                                        }; 
                                        showEditModal = true" 
                                        class="p-1.5 text-slate-400 hover:text-blue-600 transition-all cursor-pointer">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Tombol Hapus: Mengatur deleteUrl dan membuka Modal Konfirmasi -->
                                    <button @click="deleteUrl = '{{ route('admin.kelas.destroy', $k->id) }}'; showDeleteModal = true" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 transition-all cursor-pointer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-20 text-center opacity-30 text-[10px] font-black uppercase tracking-widest italic leading-relaxed">Belum ada data kelas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= MODAL TAMBAH KELAS ================= -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showModal" x-transition @click="showModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-1.5 w-full bg-[#0b1e36]"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Tambah Kelas Baru</h3>
                            <button @click="showModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kelas</label>
                                <input type="text" name="nama_kelas" required placeholder="Contoh: XII PPLG 1" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-[#0b1e36] outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Ajaran</label>
                                    <select name="tahun_ajaran" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        <option value="">Pilih</option>
                                        @php $y = date('Y'); for($i=-1;$i<=2;$i++){ $v=($y+$i)."/".($y+$i+1); echo "<option value='$v'>$v</option>"; } @endphp
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Wali Kelas</label>
                                    <select name="id_wali_kelas" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        <option value="">Pilih Guru</option>
                                        @foreach($gurus as $guru) <option value="{{ $guru->id }}">{{ $guru->name }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-[#0b1e36] text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Simpan Kelas</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL EDIT KELAS ================= -->
        <template x-teleport="body">
            <div x-show="showEditModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showEditModal" x-transition @click="showEditModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showEditModal" x-transition class="relative bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden border border-blue-100">
                    <div class="h-1.5 w-full bg-blue-600"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Perbarui Data Kelas</h3>
                            <button @click="showEditModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form :action="'/admin/kelas/' + editData.id" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Identitas Kelas</label>
                                <input type="text" name="nama_kelas" x-model="editData.nama_kelas" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-blue-600 outline-none transition-all">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tahun Ajaran</label>
                                    <select name="tahun_ajaran" x-model="editData.tahun_ajaran" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        @php $y = date('Y'); for($i=-1;$i<=2;$i++){ $v=($y+$i)."/".($y+$i+1); echo "<option value='$v'>$v</option>"; } @endphp
                                    </select>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Wali Kelas</label>
                                    <select name="id_wali_kelas" x-model="editData.id_wali_kelas" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        @foreach($gurus as $guru) <option value="{{ $guru->id }}">{{ $guru->name }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showEditModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-blue-600 text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Update Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL KONFIRMASI HAPUS ================= -->
        <template x-teleport="body">
            <div x-show="showDeleteModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4" x-cloak>
                <div x-show="showDeleteModal" x-transition @click="showDeleteModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showDeleteModal" x-transition class="relative bg-white w-full max-w-sm rounded-xl shadow-2xl overflow-hidden border border-rose-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">Hapus Kelas?</h3>
                        <p class="text-[11px] text-slate-500 mt-2">Menghapus kelas akan berdampak pada jadwal dan absensi siswa yang terhubung. Data tidak dapat dikembalikan!</p>
                        <div class="flex gap-3 mt-6">
                            <button @click="showDeleteModal = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-slate-200 transition-all cursor-pointer">BATAL</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2.5 bg-rose-600 text-white text-[10px] font-bold rounded-lg shadow-lg shadow-rose-200 transition-all uppercase tracking-widest cursor-pointer">HAPUS DATA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>

    <style> 
        [x-cloak] { display: none !important; } 
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-admin-layout>