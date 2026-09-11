<x-admin-layout>
    <!-- State Utama: Mengontrol Tab, Modal Tambah, Modal Edit, Modal Hapus, dan Modal Import -->
    <div x-data="{ 
        tab: 'siswa', 
        showModal: false, 
        showEditModal: false,
        showDeleteModal: false,
        showImportModal: false,
        deleteUrl: '',
        rolePilihan: 'siswa',
        editData: { id: '', name: '', role: '', email: '', nisn: '', nuptk: '', id_kelas: '' }
    }" class="animate-in fade-in duration-500 space-y-6 pb-12">
        
        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl font-bold text-xs flex items-center gap-3 shadow-sm">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Gagal Total -->
        @if(session('error'))
            <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl font-bold text-xs flex items-center gap-3 shadow-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Daftar Baris yang Gagal Diimport -->
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl shadow-sm">
                <p class="text-[11px] font-black text-amber-700 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    {{ count(session('import_errors')) }} Baris Gagal Diimport (baris lain tetap berhasil)
                </p>
                <ul class="space-y-1">
                    @foreach(session('import_errors') as $err)
                        <li class="text-[11px] text-amber-700">• {{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ================= SECTION 1: HEADER ================= -->
        <div class="bg-white p-5 rounded-xl border border-slate-200/50 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-[#0b1e36] text-white rounded-lg flex items-center justify-center shadow-lg">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div class="space-y-0.5">
                    <h2 class="text-lg font-extrabold text-[#0b1e36] tracking-tight">Kelola Akun</h2>
                    <p class="text-[11px] text-slate-500 font-medium">Database Siswa & Tenaga Pendidik</p>
                </div>
            </div>
            
            <div class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-2">
                <button @click="showImportModal = true" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-white text-[#0b1e36] border border-slate-200 text-[11px] font-bold rounded-lg shadow-sm hover:bg-slate-50 transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Import Excel
                </button>
                <button @click="showModal = true" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-[#0b1e36] text-white text-[11px] font-bold rounded-lg shadow-lg hover:bg-slate-800 transition-all active:scale-95 cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Akun Baru
                </button>
            </div>
        </div>

        <!-- ================= SECTION 2: TAB NAVIGASI ================= -->
        <div class="flex items-center gap-1 bg-slate-200/50 p-1 rounded-xl w-full md:w-fit border border-slate-200/60 shadow-inner">
            <button @click="tab = 'siswa'" 
                    :class="tab === 'siswa' ? 'bg-white text-sky-600 shadow-sm font-bold border-slate-200' : 'text-slate-400 border-transparent'"
                    class="flex-1 md:flex-none px-6 py-2 rounded-lg text-[11px] transition-all border flex items-center justify-center gap-2">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i> Data Siswa
            </button>
            <button @click="tab = 'guru'" 
                    :class="tab === 'guru' ? 'bg-white text-indigo-600 shadow-sm font-bold border-slate-200' : 'text-slate-400 border-transparent'"
                    class="flex-1 md:flex-none px-6 py-2 rounded-lg text-[11px] transition-all border flex items-center justify-center gap-2">
                <i data-lucide="briefcase" class="w-4 h-4"></i> Data Guru
            </button>
        </div>

        <!-- ================= SECTION 3: AREA TABEL ================= -->
        <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                
                <!-- TABEL DATA SISWA -->
                <div x-show="tab === 'siswa'" class="animate-in fade-in duration-300">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Informasi Siswa</th>
                                <th class="px-6 py-4">Kelas & NISN</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($users->where('role', 'siswa') as $siswa)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-[10px] border border-sky-100 uppercase">{{ substr($siswa->name, 0, 2) }}</div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 text-xs tracking-tight">{{ $siswa->name }}</span>
                                            <span class="text-[9px] text-slate-400 italic leading-none">{{ $siswa->email ?? 'no-email@sch.id' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-700 leading-none">{{ $siswa->kelas->nama_kelas ?? 'N/A' }}</span>
                                        <span class="text-[9px] text-slate-400 font-mono mt-1 italic tracking-tighter">NISN: {{ $siswa->nisn ?? '---' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded border border-emerald-100 uppercase">Aktif</span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex justify-end gap-1">
                                        <!-- Tombol Edit -->
                                        <button @click="editData = { id: '{{ $siswa->id }}', name: '{{ $siswa->name }}', role: 'siswa', email: '{{ $siswa->email }}', nisn: '{{ $siswa->nisn }}', id_kelas: '{{ $siswa->id_kelas }}' }; showEditModal = true" 
                                                class="p-1.5 text-slate-400 hover:text-sky-600 transition-all cursor-pointer"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                        
                                        <!-- Tombol Hapus -->
                                        <button @click="deleteUrl = '{{ route('admin.akun.destroy', $siswa->id) }}'; showDeleteModal = true" 
                                                class="p-1.5 text-slate-400 hover:text-rose-600 transition-all cursor-pointer"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-20 text-center opacity-30 text-[10px] font-black uppercase tracking-widest italic leading-relaxed">Belum ada data siswa</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- TABEL DATA GURU -->
                <div x-show="tab === 'guru'" style="display: none;" class="animate-in fade-in duration-300">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-white z-10 border-b border-slate-100">
                            <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Nama Pendidik</th>
                                <th class="px-6 py-4 text-center">Identitas NUPTK</th>
                                <th class="px-6 py-4 text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($users->where('role', 'guru') as $guru)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px] border border-indigo-100 uppercase">{{ substr($guru->name, 0, 2) }}</div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 text-xs tracking-tight">{{ $guru->name }}</span>
                                            <span class="text-[9px] text-slate-400 leading-none">{{ $guru->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center text-slate-600 font-mono text-[10px] font-bold">{{ $guru->nuptk ?? 'NUPTK KOSONG' }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button @click="editData = { id: '{{ $guru->id }}', name: '{{ $guru->name }}', role: 'guru', email: '{{ $guru->email }}', nuptk: '{{ $guru->nuptk }}' }; showEditModal = true" 
                                                class="p-1.5 text-slate-400 hover:text-indigo-600 transition-all cursor-pointer"><i data-lucide="edit-3" class="w-4 h-4"></i></button>
                                        <button @click="deleteUrl = '{{ route('admin.akun.destroy', $guru->id) }}'; showDeleteModal = true" 
                                                class="p-1.5 text-slate-400 hover:text-rose-600 transition-all cursor-pointer"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-20 text-center opacity-30 text-[10px] font-black uppercase tracking-widest italic leading-relaxed">Belum ada data guru</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= MODAL IMPORT EXCEL ================= -->
        <template x-teleport="body">
            <div x-show="showImportModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showImportModal" x-transition @click="showImportModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showImportModal" x-transition class="relative bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-1.5 w-full bg-emerald-500"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight leading-none">Import Akun dari Excel</h3>
                            <button @click="showImportModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>

                        <!-- Langkah 1: Unduh Template -->
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-4">
                            <p class="text-[11px] font-black text-slate-600 uppercase tracking-widest mb-1">Langkah 1</p>
                            <p class="text-xs text-slate-500 mb-3">Unduh template Excel di bawah ini, lalu isi data siswa/guru sesuai contoh yang ada di dalamnya.</p>
                            <a href="{{ route('admin.akun.template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-[#0b1e36] text-[11px] font-bold rounded-lg hover:bg-slate-100 transition-all">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Unduh Template Excel
                            </a>
                        </div>

                        <!-- Langkah 2: Upload -->
                        <form action="{{ route('admin.akun.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <p class="text-[11px] font-black text-slate-600 uppercase tracking-widest mb-1">Langkah 2</p>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Upload File yang Sudah Diisi</label>
                                <input type="file" name="file" required accept=".xlsx,.xls,.csv"
                                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-[#0b1e36] file:text-white">
                                <p class="text-[10px] text-slate-400 mt-1 ml-1">Format: .xlsx, .xls, atau .csv — maksimal 5MB</p>
                            </div>

                            <div class="bg-amber-50 border border-amber-100 rounded-lg p-3">
                                <p class="text-[10px] text-amber-700 leading-relaxed">
                                    <span class="font-black">Catatan:</span> Kolom "kelas" wajib diisi persis sama seperti nama kelas yang ada di menu Manajemen Kelas. Password akun otomatis dibuat dari NISN (siswa) atau NUPTK (guru).
                                </p>
                            </div>

                            <div class="flex gap-3 pt-2 border-t border-slate-100">
                                <button type="button" @click="showImportModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-emerald-600 text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Upload & Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL TAMBAH AKUN ================= -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showModal" x-transition @click="showModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showModal" x-transition class="relative bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-1.5 w-full bg-[#0b1e36]"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight leading-none">Registrasi Akun Baru</h3>
                            <button @click="showModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form action="{{ route('admin.akun.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none focus:border-[#0b1e36]">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Role</label>
                                    <select name="role" x-model="rolePilihan" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        <option value="siswa">Siswa</option>
                                        <option value="guru">Guru</option>
                                    </select>
                                </div>
                                <div class="space-y-1" x-show="rolePilihan === 'siswa'">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelas</label>
                                    <select name="id_kelas" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div x-show="rolePilihan === 'siswa'" class="space-y-1 animate-in fade-in zoom-in">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor NISN</label>
                                <input type="text" name="nisn" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div x-show="rolePilihan === 'guru'" class="space-y-1 animate-in fade-in zoom-in">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor NUPTK</label>
                                <input type="text" name="nuptk" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-[#0b1e36] text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Simpan Akun</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= MODAL EDIT AKUN ================= -->
        <template x-teleport="body">
            <div x-show="showEditModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" x-cloak>
                <div x-show="showEditModal" x-transition @click="showEditModal = false" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm"></div>
                <div x-show="showEditModal" x-transition class="relative bg-white w-full max-w-lg rounded-xl shadow-2xl overflow-hidden">
                    <div class="h-1.5 w-full transition-all" :class="editData.role === 'siswa' ? 'bg-sky-500' : 'bg-indigo-500'"></div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-extrabold text-[#0b1e36] tracking-tight leading-none">Perbarui Akun</h3>
                            <button @click="showEditModal = false" class="text-slate-300 hover:text-rose-500 cursor-pointer"><i data-lucide="x" class="w-5 h-5"></i></button>
                        </div>
                        <form :action="'/admin/akun/' + editData.id" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                    <input type="email" name="email" x-model="editData.email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                </div>
                                <div class="space-y-1" x-show="editData.role === 'siswa'">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelas</label>
                                    <select name="id_kelas" x-model="editData.id_kelas" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                                        @foreach($kelas as $k) <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option> @endforeach
                                    </select>
                                </div>
                            </div>
                            <div x-show="editData.role === 'siswa'" class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NISN</label>
                                <input type="text" name="nisn" x-model="editData.nisn" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div x-show="editData.role === 'guru'" class="space-y-1">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">NUPTK</label>
                                <input type="text" name="nuptk" x-model="editData.nuptk" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm outline-none">
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="showEditModal = false" class="flex-1 py-3 text-slate-400 text-[10px] font-bold uppercase tracking-widest">Batal</button>
                                <button type="submit" class="flex-[2] py-3 bg-[#0b1e36] text-white text-[10px] font-bold rounded-lg shadow-lg uppercase tracking-widest">Simpan Perubahan</button>
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
                <div x-show="showDeleteModal" x-transition class="relative bg-white w-full max-w-sm rounded-xl shadow-2xl overflow-hidden">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900 leading-tight">Konfirmasi Hapus</h3>
                        <p class="text-[11px] text-slate-500 mt-2">Apakah Anda yakin ingin menghapus akun ini secara permanen? Data tidak dapat dipulihkan kembali.</p>
                        <div class="flex gap-3 mt-6">
                            <button @click="showDeleteModal = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg transition-all cursor-pointer">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-2.5 bg-rose-600 text-white text-[10px] font-bold rounded-lg shadow-lg shadow-rose-200 transition-all uppercase tracking-widest cursor-pointer">Hapus Akun</button>
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