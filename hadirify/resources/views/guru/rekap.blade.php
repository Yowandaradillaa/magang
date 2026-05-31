<x-guru-layout>
    <style>
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .page-header-left h2 { font-size: 20px; font-weight: 800; color: #1a2535; }
        .page-header-left p { color: #5a6a80; font-size: 13px; margin-top: 3px; }
        .card { background: white; border-radius: 14px; box-shadow: 0 2px 16px rgba(15,76,117,0.10); overflow: hidden; margin-bottom: 20px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid #e2e8f0; }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #1a2535; }
        .form-row { display: flex; gap: 16px; margin-bottom: 20px; }
        .form-field { flex: 1; max-width: 200px; }
        .form-field label { display: block; font-size: 12px; font-weight: 600; color: #5a6a80; text-transform: uppercase; margin-bottom: 6px; }
        .form-field select { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px; font-family: inherit; font-size: 13.5px; outline: none; background: white; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 9px; font-family: inherit; font-size: 13px; font-weight: 600; border: none; cursor: pointer; }
        .btn-primary { background: #0f4c75; color: white; }
        .btn-success { background: #06d6a0; color: white; }
        .btn-outline { background: white; border: 1.5px solid #e2e8f0; color: #5a6a80; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #90a0b4; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; background: #f7f9fc; }
        td { padding: 12px 14px; border-bottom: 1px solid #e2e8f0; font-size: 13.5px; color: #1a2535; }
        tr:hover td { background: #f7faff; }
    </style>

    <div class="animate-in fade-in duration-300">
        <div class="page-header">
            <div class="page-header-left">
                <h2>Rekap & Export Presensi</h2>
                <p>Lihat rekap statistik dan unduh laporan berkas bulanan</p>
            </div>
            
            <!-- Tombol Export (Akan diarahkan ke route cetak nantinya) -->
            <div style="display:flex; gap:10px;">
                <a href="#" class="btn btn-outline">📄 Export PDF</a>
                <a href="#" class="btn btn-success">📊 Export Excel</a>
            </div>
        </div>

        <!-- Form Filter Rekap -->
        <form action="#" method="GET" class="form-row">
            <div class="form-field">
                <label>Pilih Kelas</label>
                <select name="kelas_id">
                    <option value="">Semua Kelas</option>
                    <option value="1" {{ request('kelas_id') == '1' ? 'selected' : '' }}>X-A</option>
                    <option value="2" {{ request('kelas_id') == '2' ? 'selected' : '' }}>X-B</option>
                </select>
            </div>
            <div class="form-field">
                <label>Bulan</label>
                <select name="bulan">
                    <option value="05" {{ request('bulan') == '05' ? 'selected' : '' }}>Mei 2026</option>
                    <option value="04" {{ request('bulan') == '04' ? 'selected' : '' }}>April 2026</option>
                </select>
            </div>
            <div class="form-field" style="display:flex; align-items:flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 18px;">
                    <i data-lucide="filter" class="w-4 h-4"></i> Terapkan
                </button>
            </div>
        </form>

        <div class="card">
            <div class="card-header"><h3>📊 Laporan Kehadiran Kelas</h3></div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 🌟 BERUBAH DI SINI: Data asli dari Database 🌟 -->
                    @forelse($rekaps ?? [] as $index => $rekap)
                        @php
                            // Menghitung persentase kehadiran
                            $total_hari = $rekap->hadir + $rekap->sakit + $rekap->izin + $rekap->alpa;
                            $persentase = $total_hari > 0 ? round(($rekap->hadir / $total_hari) * 100) : 0;
                            
                            // Menentukan warna persentase
                            $warna = $persentase >= 90 ? '#0cb47a' : ($persentase >= 75 ? '#ffd166' : '#ef476f');
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $rekap->siswa->name ?? 'Nama Siswa' }}</td>
                            <td>{{ $rekap->hadir ?? 0 }} Hari</td>
                            <td>{{ $rekap->sakit ?? 0 }} Hari</td>
                            <td>{{ $rekap->izin ?? 0 }} Hari</td>
                            <td>{{ $rekap->alpa ?? 0 }} Hari</td>
                            <td><span style="color:{{ $warna }}; font-weight:700;">{{ $persentase }}%</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 20px; color:#90a0b4;">
                                Belum ada data rekap presensi untuk bulan dan kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-guru-layout>