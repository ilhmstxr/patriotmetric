<?php

namespace App\Http\Controllers;

use App\DTO\UserDto\UserDTO;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        try {
            // data
            // nama pt
            // kategori pt
            // no pic
            // no hp pic
            // jabatan pic
            // email pic
            // password pic
            // konfirmasi password pic


            // $dto = UserDTO::formRequest($request);

            // validasi dari db, apakah ada email double
            // transaksi berhasil
            // return user

        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->back()->with('message', 'Registrasi berhasil');
    }

    public function login(Request $request)
    {
        try {
            // data
            // email 
            // password

            // pengecekkan apakah email ada di db lalu di get by service
            // pengecekkan apakah password cocok dengan data di db  
            // jika iya maka return autentikasi + session dkk
        } catch (\Throwable $th) {
            //throw $th;
        }

        // Login & hapus sesi di perangkat lain (Invalidate old session).
        return redirect()->back()->with('message', 'Login berhasil');
    }

    public function logout(Request $request)
    {
        try {
            // cek auth id
            // hapus token
            // hapus last_session_id
        } catch (\Throwable $th) {
            //throw $th;
        }
        return redirect()->route('login');
    }
}
