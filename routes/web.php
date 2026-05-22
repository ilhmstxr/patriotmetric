<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsAssetController;
use App\Http\Controllers\ComproPreviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Services\RubrikService;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Temporary debug route - DELETE after debugging
require __DIR__ . '/debug-admin.php';

// CMS assets (public, no auth)
Route::get('/cms-assets/{path}', [CmsAssetController::class, 'show'])
    ->where('path', '.*')
    ->name('cms.asset');

// public routes / compro
Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/visi-misi', function () {
    return view('visi-misi');
});

Route::get('/tim', function () {
    return view('tim');
});

Route::get('/penghargaan', function () {
    return view('penghargaan');
});

Route::get('/pengumuman', function () {
    return view('pengumuman');
});

Route::get('/panduan', function () {
    return view('panduan');
});

// Admin preview route for compro pages (authenticated only)
Route::get('/admin/compro-preview/{page}', [ComproPreviewController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('compro.preview');

// Auth pages (public - no middleware needed, auth is handled via API token)
Route::get('/masuk', function () {
    return view('auth.masuk');
})->name('login');

Route::get('/daftar', function () {
    return view('auth.daftar');
});

Route::get('/lupa-sandi', fn() => view('auth.lupa-sandi'));

Route::get('/reset-password/{token}', fn($token) => view('auth.reset-password', compact('token')));

Route::get('/verifikasi', function () {
    return view('auth.verifikasi');
})->name('verifikasi');

Route::get('/cek-email', function () {
    return view('auth.cek-email');
})->name('cek-email');

Route::get('/verifikasi-gagal', function () {
    return view('auth.verifikasi-gagal');
})->name('verifikasi-gagal');

// Dashboard pages (peserta)
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard.index');

    Route::get('/rubrik', function () {
        return view('dashboard.rubrik');
    })->name('dashboard.rubrik');

    Route::get('/hasil', function () {
        return view('dashboard.hasil');
    })->name('dashboard.hasil');

    Route::get('/panduan', function () {
        return view('dashboard.panduan');
    })->name('dashboard.panduan');
});

// Reviewer pages
Route::prefix('reviewer')->group(function () {
    Route::get('/', function () {
        return view('reviewer.index');
    })->name('reviewer.index');

    Route::get('/panduan', function () {
        return view('reviewer.panduan');
    })->name('reviewer.panduan');

    Route::get('/peserta/{id}', function ($id) {
        return view('reviewer.detail', compact('id'));
    })->name('reviewer.peserta_detail');
});
