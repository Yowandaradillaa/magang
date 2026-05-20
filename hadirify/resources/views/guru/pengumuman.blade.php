<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .card-body { padding: 20px 22px; }
        .form-field { margin-bottom: 14px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select, .form-field input, .form-field textarea { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .form-field textarea { resize: vertical; min-height: 100px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 11px 18px; border-radius: 9px; font-family: inherit; font-size: 13.5px; font-weight: 600; border: none; cursor: pointer; transition: all 0.18s; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-primary:hover { background: #1b6ca8; }
        .announcement-item { padding: 16px; border: 1.5px solid #e2e8f0; border-radius: 12px; margin-bottom: 12px; }
        .announcement-item h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #1a2535; }
        .announcement-item p { font-size: 13px; color: #5a6a80; line-height: 1.4; }
        .announcement-item .meta { font-size: 11px; color: #90a0b4; margin-top: 8px; font-weight: 600; }
        @media (max-width: 1100px) { .two-col { grid-template-columns: 1fr; } }
    </style>

    <div class="animate-in fade-in duration-300">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Kirim Pengumuman</h2>
                <p>Siarkan maklumat penting kepada para siswa wali kelas Anda</p>
            </div>
        </div>

        <div class="two-col">
            <div class="card">
                <div class="card-header"><h3>✏️ Buat Pengumuman Baru</h3></div>
                <div class="card-body">
                    <div class="form-field">
                        <label>Tujuan Kelas</label>
                        <select><option>Semua Kelas</option><option>X-A</option><option>X-B</option><option>XI-A</option></select>
                    </div>
                    <div class="form-field">
                        <label>Judul Pengumuman</label>
                        <input type="text" placeholder="Ketik judul pesan...">
                    </div>
                    <div class="form-field">
                        <label>Isi Pesan</label>
                        <textarea placeholder="Tulis isi pengumuman lengkap Anda di sini..."></textarea>
                    </div>
                    <button class="btn btn-primary" style="width:100%; justify-content:center;" onclick="alert('📢 Pengumuman berhasil disiarkan!')">📤 Kirim Pengumuman</button>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3>📋 Riwayat Pengumuman Terkirim</h3></div>
                <div class="card-body" style="padding:12px 16px;">
                    <div class="announcement-item">
                        <h4>Jadwal UTS Semester 2</h4>
                        <p>UTS dilaksanakan mulai 20–27 Mei 2026. Harap hadir tepat waktu.</p>
                        <div class="meta">Kelas X-A • 2 jam lalu • 32 Penerima</div>
                    </div>
                    <div class="announcement-item">
                        <h4>Kegiatan Pramuka Besok</h4>
                        <p>Pramuka rutin setiap Selasa jam 14.00. Harap bawa seragam lengkap.</p>
                        <div class="meta">Semua Kelas • 1 hari lalu • 128 Penerima</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guru-layout>