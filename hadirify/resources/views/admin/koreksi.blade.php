<x-admin-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 8px;}
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .card-body { padding: 20px 22px; }
        .form-row { display: flex; gap: 16px; margin-bottom: 0px; flex-wrap: wrap; }
        .form-field { flex: 1; min-width: 150px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select, .form-field input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .2s; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; vertical-align: middle; }
        .badge-alpha { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(239,71,111,0.12); color: #c0213f; }
        .status-select { padding: 6px 10px; border: 1.5px solid #e2e8f0; border-radius: 7px; font-family: inherit; font-size: 12px; font-weight: 600; cursor: pointer; outline: none; background: white; }
        .fade-in { animation: fadeIn .3s ease; }
        @@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity:1; transform:none; } }
    </style>

    <div class="fade-in">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Koreksi Absensi</h2>
                <p>Mengubah atau memperbaiki data absensi siswa jika terjadi kesalahan</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-field">
                        <label>Nama / NISN Siswa</label>
                        <input type="text" placeholder="Cari data siswa...">
                    </div>
                    <div class="form-field">
                        <label>Tanggal</label>
                        <input type="date" value="2026-05-11">
                    </div>
                    <div class="form-field">
                        <label>Kelas</label>
                        <select><option>Semua</option><option>X-A</option><option>X-B</option></select>
                    </div>
                    <div class="form-field" style="display:flex; align-items:flex-end;">
                        <button class="btn btn-primary" style="width:100%; justify-content:center;">
                            <i data-lucide="search" class="w-4 h-4"></i> Cari Data
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i data-lucide="clipboard-list" class="w-5 h-5 text-[#0f4c75]"></i>
                <h3>Data Hasil Pencarian — 11 Mei 2026</h3>
            </div>
            <table>
                <thead><tr><th>Siswa</th><th>Kelas</th><th>Mata Pelajaran</th><th>Status Awal</th><th>Koreksi Menjadi</th><th>Aksi</th></tr></thead>
                <tbody>
                    <tr>
                        <td><b>Dewi Kusuma</b></td><td>X-A</td><td>Matematika</td>
                        <td><span class="badge-alpha">Alpa</span></td>
                        <td>
                            <select class="status-select">
                                <option>A - Alpa</option>
                                <option>H - Hadir</option>
                                <option>S - Sakit</option>
                                <option>I - Izin</option>
                            </select>
                        </td>
                        <td><button class="btn btn-primary btn-sm" onclick="alert('Absensi diperbarui!')">Simpan</button></td>
                    </tr>
                    <tr>
                        <td><b>Fitri Rahayu</b></td><td>X-A</td><td>Fisika</td>
                        <td><span class="badge-alpha">Alpa</span></td>
                        <td>
                            <select class="status-select">
                                <option>A - Alpa</option>
                                <option>H - Hadir</option>
                                <option>S - Sakit</option>
                                <option>I - Izin</option>
                            </select>
                        </td>
                        <td><button class="btn btn-primary btn-sm" onclick="alert('Absensi diperbarui!')">Simpan</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>