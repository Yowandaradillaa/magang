<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .card-body { padding: 20px 22px; }
        .form-row { display: flex; gap: 16px; margin-bottom: 16px; }
        .form-field { flex: 1; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .form-field select, .form-field input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; color: #1a2535; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all .18s; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-primary:hover { background: #1b6ca8; box-shadow: 0 4px 14px rgba(15,76,117,0.3); }
        .btn-accent { background: #00b4d8; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        .qr-container { display: flex; flex-direction: column; align-items: center; padding: 32px; }
        .qr-box { width: 200px; height: 200px; border: 3px solid #e2e8f0; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 80px; background: white; margin-bottom: 20px; position: relative; overflow: hidden; }
        .qr-box::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, transparent 40%, rgba(0,180,216,0.06) 50%, transparent 60%); animation: shimmer 2.5s infinite; }
        @@keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
        .qr-timer { font-family: 'Space Mono', monospace; font-size: 36px; font-weight: 700; color: #0f4c75; }
        .qr-timer.urgent { color: #ef476f; animation: pulse 1s infinite; }
        @@keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.5; } }
        .qr-label { font-size: 13px; color: #90a0b4; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .badge-hadir { background: rgba(6,214,160,0.12); color: #0cb47a; }
        .badge-sakit { background: rgba(0,180,216,0.12); color: #0a8ba8; }
        .badge-alpha { background: rgba(239,71,111,0.12); color: #c0213f; }
        .badge-pending { background: rgba(255,209,102,0.18); color: #b07500; }
        @media (max-width: 1100px) { .two-col { grid-template-columns: 1fr; } }
    </style>

    <div class="animate-in fade-in duration-300">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Generate QR Absensi</h2>
                <p>Buat QR Code untuk sesi absensi kelas hari ini</p>
            </div>
        </div>

        <div class="two-col">
            <div class="card">
                <div class="card-header"><h3>⚙️ Konfigurasi Sesi Absensi</h3></div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="form-field">
                            <label>Kelas</label>
                            <select><option>X-A</option><option>X-B</option><option>XI-A</option><option>XI-B</option></select>
                        </div>
                        <div class="form-field">
                            <label>Mata Pelajaran</label>
                            <select><option>Matematika</option><option>Fisika</option><option>Bahasa Indonesia</option></select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label>Tanggal</label>
                            <input type="date" value="2026-05-11">
                        </div>
                        <div class="form-field">
                            <label>Jam Pelajaran</label>
                            <select><option>07:00–08:30</option><option>08:30–10:00</option><option>10:15–11:45</option></select>
                        </div>
                    </div>
                    <button class="btn btn-primary" style="width:100%; justify-content:center; padding:12px;" onclick="generateQR()">🔲 Generate QR Code</button>
                </div>
            </div>

            <div class="card" id="qr-display-card">
                <div class="card-header"><h3>🔲 QR Code Aktif</h3></div>
                <div class="qr-container">
                    <div class="qr-box" id="qr-icon">🔲</div>
                    <div class="qr-timer" id="qr-timer">05:00</div>
                    <div class="qr-label" id="qr-label">Belum dibuat — klik Generate</div>
                    <div style="display:none; gap:10px; margin-top:16px;" id="qr-actions">
                        <button class="btn btn-accent" onclick="generateQR()">🔄 QR Baru</button>
                        <button class="btn btn-outline" onclick="endSession()">⏹ Akhiri Sesi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="qr-students" style="margin-top: 20px;">
            <div class="card-header">
                <h3>👥 Status Presensi Siswa Real-Time</h3>
                <span class="badge badge-hadir" style="font-size:12px;">29 / 32 Hadir</span>
            </div>
            <table>
                <thead>
                    <tr><th>No</th><th>Nama Siswa</th><th>NISN</th><th>Status</th><th>Waktu Absen</th></tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>Ahmad Rizki</td><td>1234567890</td><td><span class="badge badge-hadir">Hadir</span></td><td>07:02 WIB</td></tr>
                    <tr><td>2</td><td>Budi Santoso</td><td>1234567891</td><td><span class="badge badge-hadir">Hadir</span></td><td>07:04 WIB</td></tr>
                    <tr><td>3</td><td>Citra Dewi</td><td>1234567892</td><td><span class="badge badge-sakit">Sakit</span></td><td>—</td></tr>
                    <tr><td>4</td><td>Dewi Kusuma</td><td>1234567893</td><td><span class="badge badge-pending">Belum Absen</span></td><td>—</td></tr>
                    <tr><td>5</td><td>Eko Prasetyo</td><td>1234567894</td><td><span class="badge badge-hadir">Hadir</span></td><td>07:06 WIB</td></tr>
                    <tr><td>6</td><td>Fitri Rahayu</td><td>1234567895</td><td><span class="badge badge-alpha">Alpa</span></td><td>—</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let qrInterval = null;
        let qrSeconds = 300;

        function generateQR() {
            if (qrInterval) clearInterval(qrInterval);
            qrSeconds = 300;
            document.getElementById('qr-icon').innerHTML = `<svg width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="10" y="10" width="50" height="50" rx="4" fill="none" stroke="#0f4c75" stroke-width="5"/><rect x="22" y="22" width="26" height="26" rx="2" fill="#0f4c75"/><rect x="100" y="10" width="50" height="50" rx="4" fill="none" stroke="#0f4c75" stroke-width="5"/><rect x="112" y="22" width="26" height="26" rx="2" fill="#0f4c75"/><rect x="10" y="100" width="50" height="50" rx="4" fill="none" stroke="#0f4c75" stroke-width="5"/><rect x="22" y="112" width="26" height="26" rx="2" fill="#0f4c75"/><rect x="72" y="10" width="8" height="8" fill="#0f4c75"/><rect x="82" y="10" width="8" height="8" fill="#0f4c75"/><rect x="72" y="22" width="8" height="8" fill="#0f4c75"/><rect x="82" y="30" width="8" height="8" fill="#0f4c75"/><rect x="72" y="40" width="8" height="8" fill="#0f4c75"/><rect x="100" y="72" width="8" height="8" fill="#0f4c75"/><rect x="112" y="72" width="8" height="8" fill="#0f4c75"/><rect x="122" y="80" width="8" height="8" fill="#0f4c75"/><rect x="100" y="90" width="8" height="8" fill="#0f4c75"/><rect x="72" y="72" width="8" height="8" fill="#0f4c75"/><rect x="72" y="82" width="8" height="8" fill="#0f4c75"/><rect x="82" y="92" width="8" height="8" fill="#0f4c75"/><rect x="72" y="100" width="8" height="8" fill="#0f4c75"/><rect x="82" y="110" width="8" height="8" fill="#0f4c75"/><rect x="72" y="120" width="8" height="8" fill="#0f4c75"/><rect x="110" y="100" width="8" height="8" fill="#0f4c75"/><rect x="120" y="110" width="8" height="8" fill="#0f4c75"/><rect x="130" y="100" width="8" height="8" fill="#0f4c75"/><rect x="140" y="120" width="8" height="8" fill="#0f4c75"/><rect x="120" y="130" width="8" height="8" fill="#0f4c75"/><rect x="100" y="140" width="8" height="8" fill="#0f4c75"/><rect x="130" y="140" width="8" height="8" fill="#0f4c75"/></svg>`;
            document.getElementById('qr-label').textContent = 'QR Aktif — Sesi Kelas Berjalan';
            document.getElementById('qr-actions').style.display = 'flex';
            updateQRTimer();
            qrInterval = setInterval(() => {
                qrSeconds--;
                if (qrSeconds <= 0) {
                    clearInterval(qrInterval);
                    document.getElementById('qr-timer').textContent = 'EXPIRED';
                    document.getElementById('qr-timer').className = 'qr-timer urgent';
                    document.getElementById('qr-label').textContent = 'QR Kadaluarsa — Silakan klik rilis QR baru';
                } else {
                    updateQRTimer();
                }
            }, 1000);
        }

        function updateQRTimer() {
            const m = Math.floor(qrSeconds / 60).toString().padStart(2,'0');
            const s = (qrSeconds % 60).toString().padStart(2,'0');
            const el = document.getElementById('qr-timer');
            el.textContent = `${m}:${s}`;
            el.className = qrSeconds <= 30 ? 'qr-timer urgent' : 'qr-timer';
        }

        function endSession() {
            if (qrInterval) clearInterval(qrInterval);
            document.getElementById('qr-timer').textContent = 'SELESAI';
            document.getElementById('qr-label').textContent = 'Sesi absensi kelas telah ditutup';
            alert('Sesi absensi resmi ditutup!');
        }
    </script>
</x-guru-layout>