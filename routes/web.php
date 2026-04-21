<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Services\RubrikService;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/panduan', function () {
    return view('panduan');
});

Route::get('/masuk', function () {
    return view('auth.masuk');
})->name('login');


// submitter
Route::post('/login', function (Request $request) {
    // Simulasi login sukses yang tembus langsung ke dashboard
    if ($request->email === 'admin@upnjatim.ac.id') {
        return redirect()->route('dashboard.index');
    }

    // Default: simulasi login untuk pendaftar baru, arahkan ke daftar ulang
    return redirect()->route('daftar-ulang');
})->name('login.post');

Route::get('/daftar-ulang', function () {
    return view('auth.daftar-ulang');
})->name('daftar-ulang');

Route::get('/daftar', function () {
    return view('auth.daftar');
});

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

Route::prefix('reviewer')->group(function () {
    Route::get('/', function () {
        return view('reviewer.index');
    })->name('reviewer.index');

    Route::get('/panduan', function () {
        return view('reviewer.panduan');
    })->name('reviewer.panduan');

    Route::get('/submitter/{id}', function ($id) {
        return view('reviewer.detail', compact('id'));
    })->name('reviewer.submitter_detail');
});
