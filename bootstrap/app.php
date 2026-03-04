<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Daftarkan middleware secara global khusus untuk API
        // $middleware->api(append: [
        //     \App\Http\Middleware\ForceJsonResponse::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // jika menggunakkan API
        // // 1. Tangani Error Validasi (422)
        // $exceptions->render(function (ValidationException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Validasi gagal',
        //             'errors' => $e->errors(),
        //         ], 422);
        //     }
        // });

        // // 2. Tangani Model/Halaman Tidak Ditemukan (404)
        // $exceptions->render(function (NotFoundHttpException $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Data atau halaman tidak ditemukan',
        //             'errors' => null,
        //         ], 404);
        //     }
        // });

        // // 3. Tangani Error Umum/Server (500)
        // $exceptions->render(function (Throwable $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Terjadi kesalahan pada server',
        //             'errors' => config('app.debug') ? $e->getMessage() : null,
        //         ], 500);
        //     }
        // });
    })->create();
