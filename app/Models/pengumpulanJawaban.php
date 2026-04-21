<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengumpulanJawaban extends Model
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
        return $this->belongsTo(pengumpulan::class, 'submission_id');
    }

    public function jawabanOpsi()
    {
        return $this->belongsTo(opsiJawaban::class, 'jawaban_id');
    }
    public function pertanyaan()
    {
        return $this->belongsTo(pertanyaan::class, 'pertanyaan_id');
    }
}
