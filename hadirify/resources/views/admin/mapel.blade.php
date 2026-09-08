<x-admin-layout>
    <!-- State Alpine.js: Mengontrol Modal Tambah, Edit, dan Hapus Mapel -->
    <div x-data="{ 
        showModal: false, 
        showEditModal: false,
        showDeleteModal: false,
        deleteUrl: '',
        editData: { id: '', nama_mapel: '', kode_mapel: '' }
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
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Manajemen Mata Pelajaran</h2>
                    <p class="text-[11px] text-slate-500 font-medium">
                        Kurikulum Sekolah • <span class="text-[#0b1e36] font-bold">{{ isset($mapels) ? count($mapels) : 0 }} Mapel Terdaftar</span>
                    </p>
                </div>
            </div>
            
            <button @click="showModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b1e36] hover:bg-slate-800 text-white text-[11px] font-bold rounded-lg shadow-lg transition-all active:scale-95 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Tambah Mapel Baru
            </button>
        </div>

        <!-- ================= SECTION 2: TABEL (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
            <div class="flex-1 overflow-y-auto no-scrollbar relative">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-white z-10 border-b border-slate-100">
                        <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <th class="px-6 py-4">Nama Mata Pelajaran</th>
                            <th class="px-6 py-4">Kode Mapel</th>
                            <th class="px-6 py-4 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        @forelse($mapels ?? [] as $m)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3 font-bold text-slate-800 text-xs flex items-center gap-3">
                                <div class="w-7 h-7 rounded bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-[10px] border border-amber-100 uppercase">
                                    {{ substr($m->nama_mapel, 0, 2) }}
                                </div>
                                {{ $m->nama_mapel }}
                            </td>
                            <td class="px-6 py-3">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 font-mono text-[10px] font-bold rounded border border-slate-200">
                                    {{ $m->kode_mapel ?? 'MIPEL-00X' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <!-- Tombol Edit -->
                                    <button @click="
                                        editData = { 
                                            id: '{{ $m->id }}', 
                                            nama_mapel: '{{ $m->nama_mapel }}', 
                                            kode_mapel: '{{ $m->kode_mapel ?? '' }}' 
                                        }; 
                                        showEditModal = true" 
                                        class="p-1.5 text-slate-400 hover:text-blue-600 transition-all cursor-pointer">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button @click="deleteUrl = '{{ route('admin.mapel.destroy', $m->id) }}'; showDeleteModal = true" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 transition-all cursor-pointer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-20 text-center opacity-30 text-[10px] font-black uppercase tracking-widest italic leading-relaxed">
                                Belum ada data mata pelajaran
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= MODAL TAMBAH MAPEL ================= -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showModal" x-transition @click="showModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-1.5 w-full bg-[#0b1e36]"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Tambah Mata Pelajaran</h3>
                            <button @click="showModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Mata Pelajaran</label>
                                <input type="text" name="nama_mapel" required placeholder="Contoh: Matematika Lanjut" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-[#0b1e36] outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel (Opsional)</label>
                                <input type="text" name="kode_mapel" placeholder="Contoh: MTM-12" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-[#0b1e36] outline-none font-mono">
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-[#0b1e36] text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Simpan Mapel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL EDIT MAPEL ================= -->
        <template x-teleport="body">
            <div x-show="showEditModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showEditModal" x-transition @click="showEditModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showEditModal" x-transition class="relative bg-white w-full max-w-md rounded-xl shadow-2xl overflow-hidden border border-blue-100">
                    <div class="h-1.5 w-full bg-blue-600"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Perbarui Mata Pelajaran</h3>
                            <button @click="showEditModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form :action="'/admin/mapel/' + editData.id" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Mata Pelajaran</label>
                                <input type="text" name="nama_mapel" x-model="editData.nama_mapel" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:border-blue-600 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel</label>
                                <input type="text" name="kode_mapel" x-model="editData.kode_mapel" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none font-mono">
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
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">Hapus Mata Pelajaran?</h3>
                        <p class="text-[11px] text-slate-500 mt-2">Menghapus data mapel ini akan mempengaruhi jadwal mengajar yang terikat.</p>
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