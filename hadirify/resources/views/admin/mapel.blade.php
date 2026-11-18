<x-admin-layout>
    <!-- State Alpine.js: Mengontrol Modal Tambah, Edit, dan Hapus Mapel -->
    <div x-data="{ 
        showModal: false, 
        showEditModal: false,
        showDeleteModal: false,
        showImportModal: false,
        deleteUrl: '',
        editData: { id: '', nama_mapel: '', kode_mapel: '' }
    }" class="animate-in fade-in duration-500 flex flex-col space-y-4 px-1 lg:h-[calc(100vh-108px)]">
        
        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="flex-none p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-md font-bold text-xs flex items-center gap-3 shadow-2xs">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Gagal Total -->
        @if(session('error'))
            <div class="flex-none p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-md font-bold text-xs flex items-center gap-3 shadow-2xs">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Daftar Baris yang Gagal Diimport -->
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="flex-none p-4 bg-amber-50 border border-amber-200 rounded-md shadow-2xs">
                <p class="text-[11px] font-black text-amber-700 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    {{ count(session('import_errors')) }} Baris Gagal Diimport
                </p>
                <ul class="space-y-1 max-h-32 overflow-y-auto pr-2">
                    @foreach(session('import_errors') as $err)
                        <li class="text-[11px] text-amber-700">• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($errors->any())
            <script>
                window.alert(@json(implode("\n", $errors->all())));
            </script>
        @endif

        <!-- ================= SECTION 1: HEADER (FIXED) ================= -->
        <div class="flex-none bg-white px-5 py-3.5 rounded-lg border border-slate-200/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-9 h-9 bg-[#0b1e36] text-white rounded-md flex items-center justify-center shadow-2xs shrink-0">
                    <i data-lucide="book-open" class="w-[18px] h-[18px]"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-base font-extrabold text-[#0b1e36] tracking-tight">Manajemen Mata Pelajaran</h2>
                    <p class="text-[11px] text-slate-500 font-medium">
                        Kurikulum Sekolah • <span class="text-[#0b1e36] font-bold">{{ isset($mapels) ? count($mapels) : 0 }} Mapel Terdaftar</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <button @click="showImportModal = true" class="w-full sm:w-auto flex items-center justify-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-md shadow-2xs transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4 stroke-[2.2] shrink-0"></i>
                    <span>Import Excel</span>
                </button>
                <button @click="showModal = true" class="w-full sm:w-auto flex items-center justify-center gap-2 px-3.5 py-2 bg-[#0b1e36] hover:bg-slate-800 text-white text-xs font-bold rounded-md shadow-2xs transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4 stroke-[2.5] shrink-0"></i>
                    <span>Tambah Mapel Baru</span>
                </button>
            </div>
        </div>

        <!-- ================= SECTION 2: TABEL (SCROLLABLE) ================= -->
        <div class="flex-1 min-h-0 bg-white rounded-lg border border-slate-200/80 shadow-2xs overflow-hidden flex flex-col">
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
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden">
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
                                <input type="text" name="nama_mapel" required placeholder="Contoh: Matematika Lanjut" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-md text-sm focus:border-[#0b1e36] outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel (Opsional)</label>
                                <input type="text" name="kode_mapel" maxlength="50" placeholder="Contoh: MTM-12" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-md text-sm focus:border-[#0b1e36] outline-none font-mono">
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="flex-1 py-2.5 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-2.5 bg-[#0b1e36] text-white text-[10px] font-bold rounded-md shadow-2xs uppercase tracking-widest">Simpan Mapel</button>
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
                <div x-show="showEditModal" x-transition class="relative bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden border border-blue-100">
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
                                <input type="text" name="nama_mapel" x-model="editData.nama_mapel" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-md text-sm focus:border-blue-600 outline-none transition-all">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kode Mapel</label>
                                <input type="text" name="kode_mapel" x-model="editData.kode_mapel" maxlength="50" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-md text-sm outline-none font-mono">
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showEditModal = false" class="flex-1 py-2.5 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-2.5 bg-blue-600 text-white text-[10px] font-bold rounded-md shadow-2xs uppercase tracking-widest">Update Data</button>
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
                <div x-show="showDeleteModal" x-transition class="relative bg-white w-full max-w-sm rounded-lg shadow-2xl overflow-hidden border border-rose-100">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-md flex items-center justify-center mx-auto mb-4 border border-rose-100">
                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">Hapus Mata Pelajaran?</h3>
                        <p class="text-[11px] text-slate-500 mt-2">Menghapus data mapel ini akan mempengaruhi jadwal mengajar yang terikat.</p>
                        <div class="flex gap-3 mt-6">
                            <button @click="showDeleteModal = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md hover:bg-slate-200 transition-all cursor-pointer">BATAL</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2.5 bg-rose-600 text-white text-[10px] font-bold rounded-md shadow-2xs shadow-rose-200 transition-all uppercase tracking-widest cursor-pointer">HAPUS DATA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL IMPORT MAPEL ================= -->
        <template x-teleport="body">
            <div x-show="showImportModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4" x-cloak>
                <div x-show="showImportModal" x-transition @click="showImportModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showImportModal" x-transition class="relative bg-white w-full max-w-md rounded-lg shadow-2xl overflow-hidden border border-slate-200">
                    <div class="h-1.5 w-full bg-emerald-600"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-[#0b1e36] leading-none">Import Mata Pelajaran</h3>
                                    <p class="text-[10px] text-slate-500 font-medium mt-0.5">Unggah file Excel/CSV data mata pelajaran</p>
                                </div>
                            </div>
                            <button @click="showImportModal = false" class="text-slate-400 hover:text-rose-500 cursor-pointer">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <!-- Kotak Unduh Template -->
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-md mb-4 flex items-center justify-between">
                            <div class="text-[11px] text-slate-600 font-medium">
                                <span class="font-bold text-[#0b1e36] block">Belum punya formatnya?</span>
                                Gunakan template standar
                            </div>
                            <a href="{{ route('admin.mapel.template') }}" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 hover:text-emerald-600 hover:border-emerald-300 rounded-md text-[10px] font-bold shadow-2xs transition-all flex items-center gap-1.5">
                                <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                Unduh Template
                            </a>
                        </div>

                        <!-- Form Import -->
                        <form action="{{ route('admin.mapel.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih File (.xlsx, .xls, .csv)</label>
                                <input type="file" name="file" required accept=".xlsx, .xls, .csv" 
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-[#0b1e36] file:text-white file:cursor-pointer hover:file:bg-slate-800 transition-all cursor-pointer">
                                <p class="text-[10px] text-slate-400 italic ml-1">Format kolom: <b>Nama Mapel, Kode Mapel</b></p>
                            </div>

                            <div class="flex gap-2.5 pt-3 border-t border-slate-100">
                                <button type="button" @click="showImportModal = false" class="flex-1 py-2.5 text-slate-400 hover:text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="flex-[2] py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-wider transition-all cursor-pointer flex items-center justify-center gap-2">
                                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                    Mulai Import
                                </button>
                            </div>
                        </form>
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