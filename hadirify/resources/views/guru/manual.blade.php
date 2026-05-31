<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .card-body { padding: 20px 22px; }
        .form-row { display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
        .form-field { flex: 1; min-width: 150px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select, .form-field input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .2s;}
        .btn-primary { background: #0f4c75; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        
        .student-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; transition: background .2s; }
        .student-row:hover { background: #f7f9fc; }
        .student-row:last-child { border-bottom: none; }
        .student-info { display: flex; align-items: center; gap: 12px; }
        .student-num { width: 28px; height: 28px; background: #e2e8f0; color: #5a6a80; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; border-radius: 8px; }
        .student-name { font-size: 14px; font-weight: 700; color: #1a2535; }
        
        .status-btns { display: flex; gap: 6px; }
        .status-btn { width: 34px; height: 34px; border-radius: 8px; border: 2px solid #e2e8f0; background: white; font-size: 13px; font-weight: 800; color: #90a0b4; cursor: pointer; transition: all .2s; outline: none; display: flex; align-items: center; justify-content: center; }
        .status-btn.h.sel { border-color: #06d6a0; background: #06d6a0; color: white; }
        .status-btn.a.sel { border-color: #ef476f; background: #ef476f; color: white; }
        .status-btn.s.sel { border-color: #ffd166; background: #ffd166; color: white; }
        .status-btn.i.sel { border-color: #00b4d8; background: #00b4d8; color: white; }
    </style>

    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Absensi Manual</h2>
                <p>Catat kehadiran siswa secara langsung saat tatap muka di kelas.</p>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #06d6a0; color: white; padding: 12px 16px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('guru.absensi-manual') }}" method="POST">
            @csrf
            
            <div class="card">
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-field">
                            <label>Pilih Jadwal Mengajar</label>
                            <select name="jadwal_id" required>
                                <option value="1">X-A — Matematika (07:00 - 08:30)</option>
                                <option value="2">X-A — Fisika (08:30 - 10:00)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Daftar Siswa</h3>
                    <div style="display:flex; gap:8px;">
                        <button type="button" class="btn btn-outline" onclick="setAllStatus('H')">Hadir Semua</button>
                    </div>
                </div>
                <div class="card-body" style="padding:0;" id="manual-list">
                    
                    @forelse(App\Models\User::where('role', 'siswa')->get() as $index => $s)
                    <div class="student-row">
                        <div class="student-info">
                            <div class="student-num">{{ $index + 1 }}</div>
                            <div class="student-name">{{ $s->name }}</div>
                        </div>
                        <div class="status-btns">
                            <input type="hidden" name="absensi_data[{{ $s->id }}]" id="input_status_{{ $s->id }}" class="status-input" value="H">

                            <button type="button" class="status-btn h sel" onclick="changeStatus({{ $s->id }}, 'H', this)">H</button>
                            <button type="button" class="status-btn a" onclick="changeStatus({{ $s->id }}, 'A', this)">A</button>
                            <button type="button" class="status-btn s" onclick="changeStatus({{ $s->id }}, 'S', this)">S</button>
                            <button type="button" class="status-btn i" onclick="changeStatus({{ $s->id }}, 'I', this)">I</button>
                        </div>
                    </div>
                    @empty
                    <div style="padding: 20px; text-align: center; color: #90a0b4;">
                        Tidak ada data siswa ditemukan.
                    </div>
                    @endforelse

                </div>
            </div>

            <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" style="padding:12px 24px; font-size:15px;">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Data Absensi
                </button>
            </div>
        </form>
    </div>

    <script>
        function changeStatus(id, status, btn) {
            // Ubah nilai input hidden agar terkirim ke database
            document.getElementById('input_status_' + id).value = status;
            
            // Ubah warna tombol UI
            const row = btn.closest('.student-row');
            row.querySelectorAll('.status-btn').forEach(b => b.classList.remove('sel'));
            btn.classList.add('sel');
        }

        function setAllStatus(status) {
            // Ubah semua hidden input
            document.querySelectorAll('.status-input').forEach(input => {
                input.value = status;
            });

            // Reset semua warna tombol
            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.classList.remove('sel');
            });
            
            // Nyalakan warna tombol yang dipilih saja
            document.querySelectorAll('.status-btn.' + status.toLowerCase()).forEach(btn => {
                btn.classList.add('sel');
            });
        }
    </script>
</x-guru-layout>