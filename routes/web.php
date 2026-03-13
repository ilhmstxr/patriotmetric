<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/pemenang', function () {
    return view('pemenang');
});

Route::get('/panduan', function () {
    return view('panduan');
});

Route::get('/masuk', function () {
    return view('auth.masuk');
});

Route::get('/daftar', function () {
    return view('auth.daftar');
});
