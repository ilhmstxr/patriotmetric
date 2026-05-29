<?php

// GET


public function index()
{
    // $data: Simulasi hasil query dari Repository (misal: Select * from rubriks)
    $data = [
        ['id' => 1, 'name' => 'Rubrik Penilaian A'],
        ['id' => 2, 'name' => 'Rubrik Penilaian B']
    ]; 
    
    return $this->successResponse(
        $data, // [Parameter 1] Memberikan seluruh list array ke frontend untuk di-render menjadi tabel/list
        'Data rubrik berhasil diambil', // [Parameter 2] Pesan log bahwa query berhasil
        200 // [Parameter 3] 200 OK: Standar absolut untuk request baca (Read) yang sukses
    );
}

// POST 

public function store(Request $request)
{
    // $newData: Simulasi hasil kembalian dari Repository setelah perintah INSERT ke database.
    $newData = [
        'id' => 3, // 'id' (Primary Key): Biasanya di-generate otomatis oleh DB (Auto Increment/UUID). Sangat penting dikembalikan ke frontend agar mereka tahu ID dari data yang baru saja mereka buat.
        'name' => $request->name // 'name': Nilai yang diambil dari inputan user (payload request)
    ]; 
    
    return $this->successResponse(
        $newData, // [Parameter 1] Mengembalikan obyek yang baru tercipta. Frontend membutuhkannya untuk update UI tanpa harus memanggil GET lagi.
        'Data rubrik berhasil dibuat', // [Parameter 2] Pesan konfirmasi
        201 // [Parameter 3] 201 Created: Standar HTTP spesifik yang menyatakan "Data sukses diciptakan di server".
    );
}

// PUT

public function update(Request $request, $id)
{
    // $updatedData: Simulasi data setelah proses UPDATE total di database
    $updatedData = [
        'id' => $id, // 'id': Diambil dari parameter URL (misal: /api/rubrik/5), untuk memastikan kita mengupdate baris yang benar.
        'name' => $request->name, // 'name': Data baru yang menggantikan data lama
        'deskripsi' => $request->deskripsi // Semua field wajib dikirim ulang oleh klien pada method PUT
    ];
    
    return $this->successResponse(
        $updatedData, // [Parameter 1] Mengembalikan wujud terbaru dari data tersebut
        'Data rubrik berhasil diperbarui total', 
        200 // [Parameter 3] 200 OK: Data berhasil diubah. (Bukan 201, karena datanya sudah ada sebelumnya, hanya dimodifikasi).
    );
}

// PATCH

public function modifyStatus(Request $request, $id)
{
    // $patchedData: Simulasi kembalian dari Repository setelah mengupdate 1 kolom spesifik
    $patchedData = [
        'id' => $id, // 'id': Primary Key target
        'status' => 'verified' // 'status': Hanya kolom ini yang disentuh oleh query, kolom seperti 'name' atau 'deskripsi' dibiarkan utuh di DB.
    ];
    
    return $this->successResponse(
        $patchedData, // [Parameter 1] Mengembalikan sebagian data yang berubah sebagai konfirmasi
        'Status rubrik berhasil diverifikasi', 
        200 // [Parameter 3] 200 OK.
    );
}

// DELETE

public function destroy($id)
{
    // Logika Service/Repository menghapus baris dengan $id tersebut.
    // Tidak ada variabel penampung data karena barisnya sudah dimusnahkan/di-hide.

    return $this->successResponse(
        null, // [Parameter 1] Diisi null (kosong), karena secara logika, jika data sudah dihapus, tidak ada lagi wujud data yang bisa dikembalikan ke frontend.
        'Data rubrik berhasil dihapus secara permanen', 
        200 // [Parameter 3] 200 OK. (Beberapa engineer menggunakan 204 No Content, tetapi menggunakan 200 dengan payload null lebih bersahabat untuk frontend membaca pesan message-nya).
    );
}

?>