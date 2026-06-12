<x-app-layout>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        .custom-shadow { box-shadow: 0 4px 20px -2px rgba(0, 97, 150, 0.08); }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>

    <div class="max-w-[1200px] mx-auto space-y-6 font-['Inter'] animate-in fade-in duration-500">
        
        <div class="bg-white p-6 md:p-8 rounded-[28px] custom-shadow border border-[#bfc7d2] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#cde5ff]/60 blur-[100px] rounded-full -mr-20 -mt-20 pointer-events-none"></div>
            
            <div class="relative z-10">
                <span class="bg-[#007abc] text-white px-3 py-1 rounded-full text-[11px] font-bold mb-3 inline-block uppercase tracking-wider">Riwayat</span>
                <h1 class="text-[32px] font-bold text-[#0b1c30] mb-2 leading-tight">Rekap Kehadiran Siswa</h1>
                <p class="text-[14px] text-[#3f4851]">Pantau seluruh catatan kehadiran dan log absensi digital Anda secara transparan.</p>
            </div>
        </div>

        <div class="bg-white rounded-[28px] custom-shadow border border-[#bfc7d2] overflow-hidden">
            <div class="border-b border-[#bfc7d2] p-6 bg-[#eff4ff]/40 flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#006196]/10 rounded-lg">
                        <span class="material-symbols-outlined text-[#006196]">history</span>
                    </div>
                    <h3 class="text-[20px] font-bold text-[#0b1c30]">Log Absensi Sekolah</h3>
                </div>
                <span class="text-[11px] font-bold text-[#006196] bg-[#cde5ff] px-3 py-1.5 rounded-full uppercase tracking-wider border border-[#95ccff]">Menampilkan {{ $history->count() }} Entri Absensi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13.5px] border-collapse">
                    <thead class="bg-[#eff4ff]/40 text-[11px] font-bold uppercase text-[#707882] tracking-widest border-b border-[#bfc7d2]">
                        <tr>
                            <th class="p-4 pl-8">Tanggal</th>
                            <th class="p-4">Mata Pelajaran</th>
                            <th class="p-4">Jam Absen</th>
                            <th class="p-4">Metode</th>
                            <th class="p-4 text-center pr-8">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#bfc7d2]/40 font-medium text-[#3f4851]">
                        @forelse($history as $item)
                        <tr class="hover:bg-[#f8f9ff] transition-colors">
                            <td class="p-4 pl-8 font-mono text-[13px] text-[#0b1c30] font-bold">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                            </td>
                            <td class="p-4 font-bold text-[#0b1c30]">
                                {{ $item->jadwal->mapel->nama_mapel ?? 'Mata Pelajaran' }}
                            </td>
                            <td class="p-4 font-mono text-[13px]">
                                {{ $item->waktu_absen ? \Carbon\Carbon::parse($item->waktu_absen)->format('H:i:s') . ' WIB' : '—' }}
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 bg-[#eff4ff] border border-[#bfc7d2] text-[#3f4851] rounded-lg text-[11px] font-bold">
                                    {{ $item->metode ?? 'Sistem' }}
                                </span>
                            </td>
                            <td class="p-4 text-center pr-8">
                                @php
                                    $statusName = 'Hadir';
                                    $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                    if ($item->status == 'I') {
                                        $statusName = 'Izin';
                                        $statusClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                    } elseif ($item->status == 'S') {
                                        $statusName = 'Sakit';
                                        $statusClass = 'bg-[#cde5ff] text-[#004a75] border-[#95ccff]';
                                    } elseif ($item->status == 'A') {
                                        $statusName = 'Alpa';
                                        $statusClass = 'bg-red-50 text-red-700 border-red-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border {{ $statusClass }}">
                                    {{ $statusName }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-[#707882] font-medium italic">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <div class="w-16 h-16 bg-[#eff4ff] rounded-full flex items-center justify-center mb-2">
                                        <span class="material-symbols-outlined text-[#bfc7d2] text-4xl">folder_open</span>
                                    </div>
                                    <span>Belum ada riwayat kehadiran yang tercatat.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>