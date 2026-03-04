<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengumpulan_jawaban extends Model
{
    /** @use HasFactory<\Database\Factories\PengumpulanJawabanFactory> */
    use HasFactory;

    protected $fillable = ['submission_id', 'question_id', 'jawaban_teks', 'tautan_bukti_drive', 'skor_sistem', 'skor_validasi_reviewer'];

    public function pengumpulan()
    {
        return $this->belongsTo(pengumpulan::class, 'submission_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(pertanyaan::class, 'question_id');
    }
}
