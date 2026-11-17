<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Tambahkan baris ini agar Laravel menerima koneksi Ngrok
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) {
            if ($exception->getStatusCode() === 419 && ! $request->expectsJson()) {
                // Jika 419 terjadi saat proses login
                if ($request->routeIs('login.proses') || $request->is('login*')) {
                    return redirect()->route('login')
                        ->withInput($request->only('login_id'))
                        ->with('error', 'Sesi formulir kedaluwarsa. Silakan masukkan kata sandi dan coba masuk kembali.');
                }

                // Jika 419 terjadi saat pengajuan izin siswa
                if ($request->routeIs('siswa.izin.ajukan')) {
                    return redirect()
                        ->route('siswa.izin')
                        ->withInput($request->only([
                            'tanggal_mulai',
                            'tanggal_selesai',
                            'jenis',
                            'alasan',
                        ]))
                        ->with('error', 'Pengajuan belum terkirim karena sesi formulir berakhir. Silakan periksa kembali data lalu kirim ulang.');
                }

                // Untuk form lainnya yang mengalami 419
                return back()
                    ->withInput($request->except(['password', '_token']))
                    ->with('error', 'Sesi halaman telah kedaluwarsa karena tidak ada aktivitas. Silakan coba kirim kembali.');
            }
        });
    })->create();