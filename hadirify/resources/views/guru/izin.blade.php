<x-guru-layout>
    <div x-data="{ tab: 'pending' }" class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out space-y-6">
        
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-60 h-60 bg-sky-500/5 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="relative z-10">
                <span class="text-[10px] font-extrabold tracking-widest text-[#0b1e36] uppercase bg-amber-500/10 border border-amber-400/20 px-3.5 py-1.5 rounded-full">Administrasi</span>
                <h1 class="text-2xl font-black text-[#0b1e36] tracking-tight mt-3">Persetujuan Izin Siswa</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-2xl font-bold flex items-center gap-2">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex border-b border-slate-200 gap-2">
            <button class="px-5 py-3.5 border-b-2 font-bold text-[13.5px] transition-all cursor-pointer"
                    :class="tab === 'pending' ? 'border-[#0b1e36] text-[#0b1e36]' : 'border-transparent text-slate-400'"
                    @click="tab = 'pending'">Menunggu Persetujuan</button>
            <button class="px-5 py-3.5 border-b-2 font-bold text-[13.5px] transition-all cursor-pointer"
                    :class="tab === 'all' ? 'border-[#0b1e36] text-[#0b1e36]' : 'border-transparent text-slate-400'"
                    @click="tab = 'all'">Riwayat Izin</button>
        </div>

        <div x-show="tab === 'pending'" class="fade-in">
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-[13.5px]">
                    <thead class="bg-slate-50 text-[10.5px] font-black uppercase text-slate-400">
                        <tr>
                            <th class="p-4 pl-8">Siswa</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($izins as $izin)
                        <tr>
                            <td class="p-4 pl-8 font-bold text-[#0b1e36]">{{ $izin->siswa->name ?? 'Siswa' }}</td>
                            <td class="p-4">{{ $izin->jenis }}</td>
                            <td class="p-4 font-mono text-[12px]">{{ $izin->tanggal_mulai }} s/d {{ $izin->tanggal_selesai }}</td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button type="submit" class="bg-emerald-600 text-white px-3 py-1 rounded-lg text-[11px] font-bold">Setujui</button>
                                    </form>
                                    <form action="{{ route('guru.izin.proses', $izin->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="bg-rose-600 text-white px-3 py-1 rounded-lg text-[11px] font-bold">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="p-12 text-center text-slate-400">Tidak ada izin tertunda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div x-show="tab === 'all'" style="display: none;" class="fade-in">
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left text-[13.5px]">
                    <thead class="bg-slate-50 text-[10.5px] font-black uppercase text-slate-400">
                        <tr>
                            <th class="p-4 pl-8">Siswa</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayat as $item)
                        <tr>
                            <td class="p-4 pl-8 font-bold">{{ $item->siswa->name ?? 'Siswa' }}</td>
                            <td class="p-4">{{ $item->jenis }}</td>
                            <td class="p-4 font-bold {{ $item->status == 'Disetujui' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $item->status }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-12 text-center text-slate-400">Belum ada riwayat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-guru-layout>