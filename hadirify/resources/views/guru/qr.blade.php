<x-guru-layout>
    <div class="animate-in fade-in duration-500 ease-out space-y-6">
        <div class="bg-white p-6 md:p-8 rounded-[28px] border border-slate-100 shadow-sm relative overflow-hidden">
            <h1 class="text-2xl font-black text-[#0b1e36]">Rilis QR Code Absensi</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-8">
                <div class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Kelas Sasaran</label>
                        <select id="class_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Mata Pelajaran</label>
                        <select id="subject_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="">Pilih Mapel</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Jadwal Mengajar</label>
                        <select id="jadwal_id" class="w-full px-4 py-2.5 rounded-xl border border-slate-200">
                            <option value="">Pilih Jadwal</option>
                            @foreach($schedules as $j)
                                <option value="{{ $j->id }}">{{ $j->hari }} - {{ $j->jam_mulai }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="mt-8">
                    <button onclick="generateQR()" class="w-full bg-sky-600 text-white py-4 rounded-xl font-bold">
                        Generate QR Code Sesi
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-100 p-8 flex flex-col items-center justify-center">
                <div class="w-56 h-56 border-2 border-dashed border-slate-200 rounded-3xl flex items-center justify-center mb-6">
                    @if(isset($qrImage))
                        <img src="data:image/svg+xml;base64,{{ $qrImage }}" class="w-full h-full">
                    @else
                        <span class="text-slate-300">QR KOSONG</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateQR() {
            const jadwalId = document.getElementById('jadwal_id').value;
            if(!jadwalId) { alert('Pilih jadwal!'); return; }
            window.location.href = "/guru/generate-qr/" + jadwalId;
        }
    </script>
</x-guru-layout>