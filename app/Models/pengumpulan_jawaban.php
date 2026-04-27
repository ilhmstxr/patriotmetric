<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanJawaban extends Model
{
    /** @use HasFactory<\Database\Factories\PengumpulanJawabanFactory> */
    use HasFactory;

    protected $table = 'pengumpulan_jawabans';

    protected $fillable = ['submission_id', 'pertanyaan_id', 'jawaban_teks', 'tautan_bukti_drive', 'skor_sistem', 'skor_validasi_reviewer'];

    public function pengumpulan()
    {
        return $this->belongsTo(Pengumpulan::class, 'submission_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
