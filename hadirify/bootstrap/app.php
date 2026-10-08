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
            if ($exception->getStatusCode() === 419
                && $request->routeIs('siswa.izin.ajukan')
                && ! $request->expectsJson()) {
                $submittedToken = $request->input('_token');
                $sessionToken = $request->hasSession() ? $request->session()->token() : null;

                Log::warning('CSRF verification failed for a leave request.', [
                    'host' => $request->getHost(),
                    'session_available' => $request->hasSession(),
                    'session_cookie_present' => $request->cookies->has((string) config('session.cookie')),
                    'submitted_token_present' => is_string($submittedToken) && $submittedToken !== '',
                    'token_matches_session' => is_string($submittedToken)
                        && is_string($sessionToken)
                        && hash_equals($sessionToken, $submittedToken),
                    'authenticated' => $request->user() !== null,
                    'content_type' => $request->header('Content-Type'),
                    'content_length' => $request->server('CONTENT_LENGTH'),
                    'parsed_fields' => array_keys($request->request->all()),
                    'uploaded_files' => array_keys($request->allFiles()),
                ]);

                return redirect()
                    ->route('siswa.izin')
                    ->withInput($request->only([
                        'tanggal_mulai',
                        'tanggal_selesai',
                        'jenis',
                        'alasan',
                    ]))
                    ->with('error', 'Pengajuan belum terkirim karena verifikasi keamanan formulir gagal. Muat ulang halaman, periksa kembali data, lalu kirim ulang.');
            }
        });
    })->create();