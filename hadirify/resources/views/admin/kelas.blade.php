<x-admin-layout>
    <div x-data="{ showModal: false }" class="animate-in fade-in duration-300 space-y-8">
        
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 bg-rose-500/10 text-rose-600 rounded-lg">
                        <i data-lucide="building" class="w-5 h-5"></i>
                    </span>
                    <h2 class="text-xl font-bold text-[#0b1e36]">Atur Kelas & Jadwal</h2>
                </div>
            </div>
            
            <button @click="showModal = true" class="flex items-center gap-1.5 px-4 py-2.5 bg-[#0b1e36] hover:bg-[#112d52] text-white text-xs font-bold rounded-xl shadow transition-all">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kelas
            </button>
        </div>

        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="showModal = false" class="bg-white rounded-2xl p-8 w-full max-w-md shadow-2xl border border-slate-100">
                <h3 class="text-lg font-bold text-[#0b1e36] mb-6">Tambah Kelas Baru</h3>
                
                <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Kelas</label>
                        <input type="text" name="nama_kelas" placeholder="Contoh: XII PPLG 1" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-rose-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" placeholder="Contoh: 2025/2026" required class="w-full px-4 py-2 rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-rose-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Wali Kelas</label>
                        <select name="id_wali_kelas" class="w-full px-4 py-2 rounded-xl border border-slate-200">
                            <option value="">Pilih Wali Kelas</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="button" @click="showModal = false" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs">Batal</button>
                        <button type="submit" class="flex-1 py-2.5 bg-rose-600 text-white font-bold rounded-xl text-xs">Simpan Kelas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>