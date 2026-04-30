<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanJawaban extends Model
{
    /** @use HasFactory<\Database\Factories\PengumpulanJawabanFactory> */
    protected $table = 'pengumpulan_jawabans';
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'pertanyaan_id',
        'jawaban_id',
        'jawaban_teks',
        'tautan_bukti_drive',
        'skor_sistem',
        'skor_validasi_reviewer'
    ];

    public function pengumpulan()
    {
        return $this->belongsTo(Pengumpulan::class, 'submission_id');
    }

    public function jawabanOpsi()
    {
        return $this->belongsTo(OpsiJawaban::class, 'jawaban_id');
    }
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
