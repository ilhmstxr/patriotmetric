<?php
use App\Models\User;
use App\Models\Institusi;
use App\Models\Pengumpulan;
use App\Models\Identitas;
use Illuminate\Support\Facades\Hash;

$user = User::firstOrCreate(
    ['email' => 'simulasitester3@example.com'],
    [
        'name' => 'Tester 3',
        'password' => Hash::make('password123'),
        'role' => 'PESERTA',
        'status' => 'ACTIVE'
    ]
);

$institusi = Institusi::firstOrCreate(
    ['nama_institusi' => 'Kampus Tester 3'],
    ['jenis_institusi' => 'PTN']
);

$pengumpulan = Pengumpulan::updateOrCreate(
    ['user_id' => $user->id],
    [
        'institution_id' => $institusi->id,
        'status' => 'IN_PROGRESS',
        'tahun_periode' => 2026,
        'nama_pic' => 'Bapak PIC',
        'jabatan_pic' => 'Manajer',
        'no_hp_pic' => '0812345',
        'email_pic' => 'pic@test.com'
    ]
);

Identitas::updateOrCreate(
    ['pengumpulan_id' => $pengumpulan->id],
    [
        'visi' => 'Visi test',
        'misi' => 'Misi test',
        'jml_mahasiswa' => 100,
        'jml_dosen' => 10,
        'jml_tendik' => 5,
        'jml_prodi' => 2,
        'jml_ukm' => 1,
        'jml_ormawa' => 1,
        'is_verified' => true
    ]
);

echo "User created with IN_PROGRESS status.\n";
