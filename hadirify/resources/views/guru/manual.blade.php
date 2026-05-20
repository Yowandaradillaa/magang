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
        .form-field select, .form-field input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 7px; }
        .student-row { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-bottom: 1px solid #e2e8f0; }
        .student-num { width: 24px; height: 24px; background: #f0f4f8; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #90a0b4; }
        .student-name { flex: 1; font-size: 13.5px; font-weight: 600; color: #1a2535; }
        .status-btns { display: flex; gap: 4px; }
        .status-btn { width: 34px; height: 28px; border: 1.5px solid #e2e8f0; border-radius: 6px; background: white; font-family: 'Space Mono', monospace; font-size: 11px; font-weight: 700; cursor: pointer; color: #90a0b4; transition: all 0.15s; }
        .status-btn.h.sel { background: rgba(6,214,160,0.15); border-color: #06d6a0; color: #0cb47a; }
        .status-btn.a.sel { background: rgba(239,71,111,0.12); border-color: #ef476f; color: #ef476f; }
        .status-btn.s.sel { background: rgba(0,180,216,0.12); border-color: #00b4d8; color: #0a8ba8; }
        .status-btn.i.sel { background: rgba(255,209,102,0.18); border-color: #ffd166; color: #b07500; }
    </style>

    <div class="animate-in fade-in duration-300" onload="initManualView()">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Input Absensi Manual</h2>
                <p>Tandai atau koreksi kehadiran siswa secara mandiri</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="form-row" style="margin-bottom:0;">
                    <div class="form-field">
                        <label>Kelas</label>
                        <select><option>X-A (32 siswa)</option><option>X-B (30 siswa)</option></select>
                    </div>
                    <div class="form-field">
                        <label>Tanggal</label>
                        <input type="date" value="2026-05-11">
                    </div>
                    <div class="form-field">
                        <label>Mata Pelajaran</label>
                        <select><option>Matematika</option><option>Fisika</option></select>
                    </div>
                    <div class="form-field" style="display:flex; align-items:flex-end;">
                        <button class="btn btn-primary" style="width:100%; justify-content:center; padding:11px;">Muat Daftar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>📋 Daftar Hadir Kelas Siswa</h3>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-outline btn-sm" onclick="setAllStatus('H')">Hadir Semua</button>
                    <button class="btn btn-primary btn-sm" onclick="saveManual()">💾 Simpan Absen</button>
                </div>
            </div>
            <div id="student-list-container">
                </div>
        </div>
    </div>

    <script>
        const students = ['Ahmad Rizki','Budi Santoso','Citra Dewi','Dewi Kusuma','Eko Prasetyo','Fitri Rahayu','Gilang Saputra','Hani Pertiwi','Irfan Maulana','Joko Widodo'];
        let studentStatuses = {};

        // Inisialisasi data otomatis saat file diload
        document.addEventListener("DOMContentLoaded", function() {
            students.forEach((s, i) => { studentStatuses[i] = 'H'; });
            renderManualList();
        });

        function renderManualList() {
            const container = document.getElementById('student-list-container');
            if (!container) return;
            let html = '';
            students.forEach((name, i) => {
                const cur = studentStatuses[i] || 'H';
                html += `
                <div class="student-row">
                    <div class="student-num">${i+1}</div>
                    <div class="student-name">${name}</div>
                    <div class="status-btns">
                        <button class="status-btn h ${cur==='H'?'sel':''}" onclick="changeStatus(${i},'H',this)">H</button>
                        <button class="status-btn a ${cur==='A'?'sel':''}" onclick="changeStatus(${i},'A',this)">A</button>
                        <button class="status-btn s ${cur==='S'?'sel':''}" onclick="changeStatus(${i},'S',this)">S</button>
                        <button class="status-btn i ${cur==='I'?'sel':''}" onclick="changeStatus(${i},'I',this)">I</button>
                    </div>
                </div>`;
            });
            container.innerHTML = html;
        }

        function changeStatus(idx, status, btn) {
            studentStatuses[idx] = status;
            const row = btn.closest('.student-row');
            row.querySelectorAll('.status-btn').forEach(b => b.classList.remove('sel'));
            btn.classList.add('sel');
        }

        function setAllStatus(s) {
            students.forEach((_, i) => studentStatuses[i] = s);
            renderManualList();
        }

        function saveManual() {
            alert('📋 Data absensi manual siswa berhasil disimpan ke database!');
        }
    </script>
</x-guru-layout>