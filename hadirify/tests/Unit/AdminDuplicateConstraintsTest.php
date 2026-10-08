<?php

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\QRCode;
use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->nullable()->unique();
        $table->string('nisn')->nullable()->unique();
        $table->string('nuptk')->nullable()->unique();
        $table->unsignedBigInteger('id_kelas')->nullable();
        $table->string('role');
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();
    });
    Schema::create('kelas', function (Blueprint $table) {
        $table->id();
        $table->string('nama_kelas');
        $table->string('mata_pelajaran')->nullable();
        $table->string('tahun_ajaran');
        $table->unsignedBigInteger('id_wali_kelas')->nullable();
        $table->timestamps();
        $table->unique(['nama_kelas', 'tahun_ajaran'], 'kelas_name_year_unique');
    });
    Schema::create('mata_pelajarans', function (Blueprint $table) {
        $table->id();
        $table->string('nama_mapel');
        $table->string('kode_mapel')->nullable()->unique();
        $table->text('deskripsi')->nullable();
        $table->timestamps();
        $table->unique('nama_mapel', 'mata_pelajarans_name_unique');
    });
    Schema::create('jadwals', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_kelas');
        $table->unsignedBigInteger('id_mapel');
        $table->unsignedBigInteger('id_guru');
        $table->string('hari');
        $table->time('jam_mulai');
        $table->time('jam_selesai');
        $table->timestamps();
        $table->unique(['id_kelas', 'hari', 'jam_mulai', 'jam_selesai'], 'jadwals_class_slot_unique');
        $table->unique(['id_guru', 'hari', 'jam_mulai', 'jam_selesai'], 'jadwals_teacher_slot_unique');
    });
    Schema::create('pengumumen', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('guru_id');
        $table->unsignedBigInteger('kelas_id');
        $table->string('judul');
        $table->text('isi');
        $table->timestamp('tanggal');
        $table->timestamps();
    });
    Schema::create('pengajuan_izins', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('siswa_id');
        $table->unsignedBigInteger('id_guru_approver')->nullable();
        $table->date('tanggal_mulai');
        $table->date('tanggal_selesai');
        $table->string('jenis');
        $table->text('alasan');
        $table->string('file_surat')->nullable();
        $table->string('status');
        $table->text('catatan_guru')->nullable();
        $table->dateTime('tanggal_pengajuan');
        $table->timestamps();
    });
    Schema::create('q_r_codes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('jadwal_id');
        $table->unsignedBigInteger('guru_id');
        $table->string('kode_qr')->unique();
        $table->dateTime('waktu_dibuat');
        $table->dateTime('waktu_expired');
        $table->string('status');
        $table->timestamps();
    });
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('siswa_id');
        $table->unsignedBigInteger('jadwal_id');
        $table->date('tanggal');
        $table->string('status');
        $table->string('metode');
        $table->decimal('latitude', 10, 7)->nullable();
        $table->decimal('longitude', 10, 7)->nullable();
        $table->dateTime('waktu_absen');
        $table->unsignedBigInteger('dikoreksi_oleh')->nullable();
        $table->timestamps();
        $table->unique(['siswa_id', 'jadwal_id', 'tanggal'], 'absensis_student_schedule_date_unique');
    });

    $this->admin = User::create([
        'name' => 'Admin Sekolah',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    $this->guru = User::create([
        'name' => 'Guru Satu',
        'email' => 'guru1@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'guru',
    ]);
    $this->guruLain = User::create([
        'name' => 'Guru Dua',
        'email' => 'guru2@gmail.com',
        'password' => Hash::make('password'),
        'role' => 'guru',
    ]);
    $this->siswa = User::create([
        'name' => 'Siswa Satu',
        'email' => 'siswa1@gmail.com',
        'nisn' => '1111111111',
        'password' => Hash::make('password'),
        'role' => 'siswa',
    ]);
    $this->kelas = Kelas::create([
        'nama_kelas' => 'X RPL 1',
        'tahun_ajaran' => '2026/2027',
    ]);
    $this->kelasLain = Kelas::create([
        'nama_kelas' => 'X RPL 2',
        'tahun_ajaran' => '2026/2027',
    ]);
    $this->mapel = MataPelajaran::create([
        'nama_mapel' => 'Matematika',
        'kode_mapel' => 'MTK-01',
    ]);
    $this->actingAs($this->admin);
});

afterEach(function () {
    Schema::dropIfExists('pengajuan_izins');
    Schema::dropIfExists('absensis');
    Schema::dropIfExists('q_r_codes');
    Schema::dropIfExists('pengumumen');
    Schema::dropIfExists('jadwals');
    Schema::dropIfExists('mata_pelajarans');
    Schema::dropIfExists('kelas');
    Schema::dropIfExists('users');
});

function jadwalPayload(array $overrides = []): array
{
    return array_merge([
        'id_kelas' => 1,
        'id_mapel' => 1,
        'id_guru' => 2,
        'hari' => 'Senin',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:00',
    ], $overrides);
}

test('jadwal menolak bentrok waktu pada guru yang sama', function () {
    Jadwal::create([
        'id_kelas' => $this->kelas->id,
        'id_mapel' => $this->mapel->id,
        'id_guru' => $this->guru->id,
        'hari' => 'Senin',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:00',
    ]);

    $this->from('/admin/kelas')
        ->post(route('admin.jadwal.store'), jadwalPayload([
            'id_kelas' => $this->kelasLain->id,
            'id_guru' => $this->guru->id,
            'jam_mulai' => '09:30',
            'jam_selesai' => '10:30',
        ]))
        ->assertRedirect('/admin/kelas')
        ->assertSessionHasErrorsIn('jadwal', ['id_guru'])
        ->assertSessionHasInput('jam_mulai', '09:30')
        ->assertSessionHasInput('jam_selesai', '10:30');

    expect(Jadwal::count())->toBe(1);
});

test('jadwal menolak bentrok waktu pada kelas yang sama', function () {
    Jadwal::create([
        'id_kelas' => $this->kelas->id,
        'id_mapel' => $this->mapel->id,
        'id_guru' => $this->guru->id,
        'hari' => 'Senin',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:00',
    ]);

    $this->from('/admin/kelas')
        ->post(route('admin.jadwal.store'), jadwalPayload([
            'id_kelas' => $this->kelas->id,
            'id_guru' => $this->guruLain->id,
            'jam_mulai' => '09:30',
            'jam_selesai' => '10:30',
        ]))
        ->assertRedirect('/admin/kelas')
        ->assertSessionHasErrorsIn('jadwal', ['id_kelas'])
        ->assertSessionHasInput('jam_mulai', '09:30')
        ->assertSessionHasInput('jam_selesai', '10:30');

    expect(Jadwal::count())->toBe(1);
});

test('jadwal yang tidak bertumpang tindih boleh ditambahkan', function () {
    Jadwal::create([
        'id_kelas' => $this->kelas->id,
        'id_mapel' => $this->mapel->id,
        'id_guru' => $this->guru->id,
        'hari' => 'Senin',
        'jam_mulai' => '09:00',
        'jam_selesai' => '10:00',
    ]);

    $this->post(route('admin.jadwal.store'), jadwalPayload([
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:00',
    ]))->assertSessionHasNoErrors();

    expect(Jadwal::count())->toBe(2);
});

test('kelas dengan nama dan tahun ajaran yang sama tidak dapat diduplikasi', function () {
    $this->from('/admin/kelas')
        ->post(route('admin.kelas.store'), [
            'nama_kelas' => $this->kelas->nama_kelas,
            'tahun_ajaran' => $this->kelas->tahun_ajaran,
        ])
        ->assertRedirect('/admin/kelas')
        ->assertSessionHasErrors('nama_kelas');

    expect(Kelas::count())->toBe(2);
});

test('mata pelajaran dengan nama yang sama tidak dapat diduplikasi', function () {
    $this->from('/admin/mapel')
        ->post(route('admin.mapel.store'), ['nama_mapel' => $this->mapel->nama_mapel])
        ->assertRedirect('/admin/mapel')
        ->assertSessionHasErrors('nama_mapel');

    expect(MataPelajaran::count())->toBe(1);
});

test('kode mata pelajaran yang sudah digunakan tidak dapat diduplikasi', function () {
    $this->from('/admin/mapel')
        ->post(route('admin.mapel.store'), [
            'nama_mapel' => 'Fisika',
            'kode_mapel' => 'MTK-01',
        ])
        ->assertSessionHasErrors('kode_mapel');

    expect(MataPelajaran::count())->toBe(1);
});

test('email akun yang sudah digunakan tidak dapat diduplikasi', function () {
    $this->from('/admin/akun')
        ->post(route('admin.akun.store'), [
            'name' => 'Siswa Baru',
            'role' => 'siswa',
            'email' => $this->admin->email,
            'nisn' => '1234567890',
            'password' => 'PasswordAman123',
            'password_confirmation' => 'PasswordAman123',
        ])
        ->assertRedirect('/admin/akun')
        ->assertSessionHasErrors('email');

    expect(User::count())->toBe(4);
});

test('admin dapat menetapkan password unik saat membuat akun', function () {
    $response = $this->post(route('admin.akun.store'), [
        'name' => 'Siswa Baru',
        'role' => 'siswa',
        'email' => 'siswa@gmail.com',
        'nisn' => '1234567890',
        'password' => 'PasswordAman123',
        'password_confirmation' => 'PasswordAman123',
    ]);

    $response->assertSessionHasNoErrors();

    $siswa = User::where('email', 'siswa@gmail.com')->firstOrFail();
    expect(Hash::check('PasswordAman123', $siswa->password))->toBeTrue()
        ->and(Hash::check('1111111111', $siswa->password))->toBeFalse();
});

test('pembuatan akun menolak password yang tidak memenuhi syarat', function () {
    $this->from('/admin/akun')
        ->post(route('admin.akun.store'), [
            'name' => 'Siswa Baru',
            'role' => 'siswa',
            'email' => 'siswa@gmail.com',
            'nisn' => '1234567890',
            'password' => 'short',
            'password_confirmation' => 'different',
        ])
        ->assertRedirect('/admin/akun')
        ->assertSessionHasErrors(['password']);

    expect(User::count())->toBe(4);
});

test('pengumuman identik untuk kelas yang sama tidak dapat dikirim dua kali', function () {
    $this->actingAs($this->guru)
        ->post(route('guru.pengumuman.send'), [
            'kelas_id' => $this->kelas->id,
            'judul' => 'Jadwal Ujian',
            'isi' => 'Ujian dilaksanakan hari Senin.',
        ])
        ->assertSessionHasNoErrors();

    $this->from('/guru/pengumuman')
        ->post(route('guru.pengumuman.send'), [
            'kelas_id' => $this->kelas->id,
            'judul' => 'Jadwal Ujian',
            'isi' => 'Ujian dilaksanakan hari Senin.',
        ])
        ->assertRedirect('/guru/pengumuman')
        ->assertSessionHasErrors('judul');

    expect(Pengumuman::count())->toBe(1);
});

test('izin dengan tanggal dan isian sama tidak dapat diajukan dua kali', function () {
    $payload = [
        'tanggal_mulai' => '2026-10-12',
        'tanggal_selesai' => '2026-10-13',
        'jenis' => 'Izin',
        'alasan' => 'Keperluan keluarga',
    ];

    $this->actingAs($this->siswa)
        ->post(route('siswa.izin.ajukan'), $payload)
        ->assertSessionHasNoErrors();

    $this->from('/siswa/izin')
        ->post(route('siswa.izin.ajukan'), $payload)
        ->assertRedirect('/siswa/izin')
        ->assertSessionHasErrors([
            'tanggal_mulai' => 'Pengajuan izin dengan tanggal, jenis, dan alasan yang sama sudah pernah dikirim.',
        ])
        ->assertSessionHasInput('tanggal_mulai', '2026-10-12')
        ->assertSessionHasInput('tanggal_selesai', '2026-10-13');

    expect(\App\Models\PengajuanIzin::count())->toBe(1);
});

test('izin menolak tanggal selesai yang lebih awal dari tanggal mulai', function () {
    $this->actingAs($this->siswa)
        ->from('/siswa/izin')
        ->post(route('siswa.izin.ajukan'), [
            'tanggal_mulai' => '2026-10-14',
            'tanggal_selesai' => '2026-10-13',
            'jenis' => 'Sakit',
            'alasan' => 'Sedang sakit.',
        ])
        ->assertRedirect('/siswa/izin')
        ->assertSessionHasErrors([
            'tanggal_selesai' => 'Tanggal selesai izin tidak boleh lebih awal daripada tanggal mulai.',
        ]);

    expect(\App\Models\PengajuanIzin::count())->toBe(0);
});

test('form izin tidak disimpan di cache browser', function () {
    $response = $this->actingAs($this->siswa)->get(route('siswa.izin'));

    $response->assertOk()->assertSee('name="_token"', false);
    expect($response->headers->get('Cache-Control'))->toContain('no-store');
});

function buatQrUntukSiswa(TestCase $test, ?int $kelasId = null): QRCode
{
    $test->siswa->update(['id_kelas' => $kelasId ?? $test->kelas->id]);

    $jadwal = Jadwal::create([
        'id_kelas' => $kelasId ?? $test->kelas->id,
        'id_mapel' => $test->mapel->id,
        'id_guru' => $test->guru->id,
        'hari' => now()->locale('id')->dayName,
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:00:00',
    ]);

    return QRCode::create([
        'jadwal_id' => $jadwal->id,
        'guru_id' => $test->guru->id,
        'kode_qr' => 'qr-' . uniqid(),
        'waktu_dibuat' => now(),
        'waktu_expired' => now()->addMinutes(15),
        'status' => 'aktif',
    ]);
}

test('scan QR berhasil sekali dan scan kedua untuk jadwal yang sama ditolak', function () {
    $qr = buatQrUntukSiswa($this);
    $payload = [
        'kode_qr' => $qr->kode_qr,
        'latitude' => config('attendance.school.latitude'),
        'longitude' => config('attendance.school.longitude'),
    ];

    $this->actingAs($this->siswa)
        ->postJson(route('siswa.scan.proses'), $payload)
        ->assertOk()
        ->assertJsonPath('status', 'success');

    $this->postJson(route('siswa.scan.proses'), $payload)
        ->assertUnprocessable()
        ->assertJsonPath('message', 'Absensi untuk jadwal ini sudah tercatat hari ini dengan status Hadir.');

    expect(\App\Models\Absensi::count())->toBe(1);
});

test('scan QR ditolak jika siswa sudah memiliki absensi manual sakit izin atau alfa', function (string $status) {
    $qr = buatQrUntukSiswa($this);
    \App\Models\Absensi::create([
        'siswa_id' => $this->siswa->id,
        'jadwal_id' => $qr->jadwal_id,
        'tanggal' => now()->toDateString(),
        'status' => $status,
        'metode' => 'Manual',
        'waktu_absen' => now(),
    ]);

    $this->actingAs($this->siswa)
        ->postJson(route('siswa.scan.proses'), [
            'kode_qr' => $qr->kode_qr,
            'latitude' => config('attendance.school.latitude'),
            'longitude' => config('attendance.school.longitude'),
        ])
        ->assertUnprocessable();

    expect(\App\Models\Absensi::count())->toBe(1);
})->with(['manual-hadir' => 'H', 'sakit' => 'S', 'izin' => 'I', 'alfa' => 'A']);

test('scan QR ditolak jika berada lebih dari 200 meter dari sekolah', function () {
    $qr = buatQrUntukSiswa($this);

    $this->actingAs($this->siswa)
        ->postJson(route('siswa.scan.proses'), [
            'kode_qr' => $qr->kode_qr,
            'latitude' => config('attendance.school.latitude') + 0.00225,
            'longitude' => config('attendance.school.longitude'),
        ])
        ->assertForbidden()
        ->assertJsonPath('message', 'Absensi hanya dapat dilakukan dalam radius maksimal 200 meter dari sekolah.');

    expect(\App\Models\Absensi::count())->toBe(0);
});

test('scan QR tidak menerima lokasi di luar koordinat yang valid', function () {
    $qr = buatQrUntukSiswa($this);

    $this->actingAs($this->siswa)
        ->postJson(route('siswa.scan.proses'), [
            'kode_qr' => $qr->kode_qr,
            'latitude' => 91,
            'longitude' => 110,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('latitude');

    expect(\App\Models\Absensi::count())->toBe(0);
});

test('scan QR untuk kelas lain ditolak', function () {
    $qr = buatQrUntukSiswa($this, $this->kelasLain->id);
    $this->siswa->update(['id_kelas' => $this->kelas->id]);

    $this->actingAs($this->siswa)
        ->postJson(route('siswa.scan.proses'), [
            'kode_qr' => $qr->kode_qr,
            'latitude' => config('attendance.school.latitude'),
            'longitude' => config('attendance.school.longitude'),
        ])
        ->assertForbidden();

    expect(\App\Models\Absensi::count())->toBe(0);
});

test('halaman scan menampilkan status absensi siswa yang sebenarnya', function () {
    $qr = buatQrUntukSiswa($this);
    \App\Models\Absensi::create([
        'siswa_id' => $this->siswa->id,
        'jadwal_id' => $qr->jadwal_id,
        'tanggal' => now()->toDateString(),
        'status' => 'S',
        'metode' => 'Manual',
        'waktu_absen' => now(),
    ]);

    $this->actingAs($this->siswa)
        ->get(route('siswa.scan-qr'))
        ->assertOk()
        ->assertSee('Sakit');
});

test('import akun menolak identifier yang berulang dalam file dan yang sudah terdaftar', function () {
    $import = new UsersImport();
    $import->collection(new Collection([
        [
            'nama' => 'Siswa Baru',
            'role' => 'siswa',
            'email' => 'siswa.baru@gmail.com',
            'nisn' => '2222222222',
            'nuptk' => '',
            'kelas' => 'X RPL 1',
        ],
        [
            'nama' => 'Siswa Duplikat',
            'role' => 'siswa',
            'email' => 'SISWA.BARU@gmail.com',
            'nisn' => '2222222222',
            'nuptk' => '',
            'kelas' => 'X RPL 1',
        ],
        [
            'nama' => 'NISN Sudah Ada',
            'role' => 'siswa',
            'email' => 'siswa.lama@gmail.com',
            'nisn' => '1111111111',
            'nuptk' => '',
            'kelas' => 'X RPL 1',
        ],
    ]));

    expect($import->getSuccessCount())->toBe(1)
        ->and(count($import->getErrors()))->toBe(2)
        ->and(User::where('nisn', '2222222222')->count())->toBe(1);
});
