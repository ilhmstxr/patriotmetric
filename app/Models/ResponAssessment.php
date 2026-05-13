<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponAssessment extends Model
{
    /** @use HasFactory<\Database\Factories\ResponAssessmentFactory> */
    protected $table = 'respon_assessments';
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'pertanyaan_id',
        'jawaban_id',
        'jawaban_teks',
        'tautan_bukti_drive',
        'skor_sistem',
        'skor_validasi_reviewer'
    ];

    protected function casts(): array
    {
        return [
            'jawaban_teks' => 'array',
        ];
    }

    public function Assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
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
