<?php

namespace App\DTO\AuthDTO;

readonly class RegisterDTO
{
    public string $namaPt;
    public string $jenisPt;
    public string $namaPic;
    public string $noHpPic;
    public string $jabatanPic;
    public string $email;
    public string $password;

    public function __construct(array $validated)
    {
        $this->namaPt = $validated['nama_pt'];
        $this->jenisPt = $validated['jenis_pt'];
        $this->namaPic = $validated['nama_pic'];
        $this->noHpPic = $validated['no_hp_pic'];
        $this->jabatanPic = $validated['jabatan_pic'];
        $this->email = $validated['email'];
        $this->password = $validated['password']; // Password mentah untuk hashing di Service
    }
}
