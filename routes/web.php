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


// peserta
Route::post('/login', function (Request $request) {
    // Simulasi login sukses yang tembus langsung ke dashboard
    if ($request->email === 'admin@upnjatim.ac.id') {
        return redirect()->route('dashboard.index');
    }

    // Default: simulasi login untuk pendaftar baru, arahkan ke daftar ulang
    return redirect()->route('verifikasi');
})->name('login.post');

Route::get('/verifikasi', function () {
    return view('auth.verifikasi');
})->name('verifikasi');

// jembot nak kene trnyt
Route::get('/daftar', function () {
    return view('auth.daftar');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard.index');

    Route::get('/rubrik', function () {
        // Fallback untuk development: jika yang login adalah admin/reviewer (tidak punya assessment), pakai assessment user 3.
        $userId = \Illuminate\Support\Facades\Auth::id() ?? 3;
        $assessment = \App\Models\Pengumpulan::where('user_id', $userId)->where('status', 'ACTIVE')->first();
        
        // Jika tidak ketemu (karena login sbg reviewer dsb), paksa ke user 3 untuk testing peserta
        if (!$assessment) {
            $assessment = \App\Models\Pengumpulan::where('user_id', 3)->where('status', 'ACTIVE')->first();
        }

        $assessmentId = $assessment ? $assessment->id : 0;
        return view('dashboard.rubrik', compact('assessmentId'));
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

    Route::get('/peserta/{id}', function ($id) {
        return view('reviewer.detail', compact('id'));
    })->name('reviewer.peserta_detail');
});
