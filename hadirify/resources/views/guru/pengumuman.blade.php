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
        .form-field input, .form-field textarea { width: 100%; padding: 12px 16px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 14px; outline: none; transition: border-color .2s; }
        .form-field input:focus, .form-field textarea:focus { border-color: #0f4c75; }
        .form-field textarea { resize: vertical; min-height: 120px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 12px 20px; border-radius: 10px; font-family: inherit; font-size: 14px; font-weight: 700; border: none; cursor: pointer; transition: all .2s; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-primary:hover { background: #1b6ca8; }
        .announcement-item { padding-bottom: 16px; border-bottom: 1px solid #e2e8f0; margin-bottom: 16px; }
        .announcement-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .announcement-item h4 { font-size: 14.5px; font-weight: 800; color: #1a2535; margin-bottom: 4px; }
        .announcement-item p { font-size: 13px; color: #5a6a80; line-height: 1.6; }
        .announcement-item .meta { font-size: 11px; font-weight: 700; color: #90a0b4; margin-top: 8px; }
    </style>

    <div class="animate-in fade-in slide-in-from-bottom-8 duration-500 ease-out">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Pusat Pengumuman</h2>
                <p>Siarkan pesan, informasi, atau tugas baru kepada seluruh siswa di kelas Anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #06d6a0; color: white; padding: 12px 16px; border-radius: 10px; font-weight: bold; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="two-col">
            <div class="card">
                <div class="card-header"><h3>Tulis Pengumuman Baru</h3></div>
                <div class="card-body">
                    
                    <form action="{{ route('guru.pengumuman.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kelas_id" value="1"> 

                        <div class="form-field">
                            <label>Judul Pengumuman</label>
                            <input type="text" name="judul" placeholder="Ketik judul pesan..." required>
                        </div>
                        <div class="form-field">
                            <label>Isi Pesan</label>
                            <textarea name="isi" placeholder="Tulis isi pengumuman lengkap Anda di sini..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">📤 Kirim Pengumuman</button>
                    </form>
                    
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3>📋 Riwayat Pengumuman Terkirim</h3></div>
                <div class="card-body" style="padding:12px 16px;">
                    <div class="announcement-item">
                        <h4>Data riwayat akan muncul di sini...</h4>
                        <p>Kirim pengumuman pertamamu lewat form di sebelah kiri!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guru-layout>