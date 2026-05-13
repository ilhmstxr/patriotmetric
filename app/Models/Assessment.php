<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'reviewer_id', 'institution_id', 'tahun_periode', 'status', 'total_skor_sistem', 'total_skor_akhir', 'skor_rekap_json', 'nama_pic', 'jabatan_pic', 'no_hp_pic'];

    protected $casts = [
        'skor_rekap_json' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Reviewer::class, 'reviewer_id');
    }

    public function jawabans()
    {
        return $this->hasMany(ResponAssessment::class, 'assessment_id');
    }

    public function identitas()
    {
        return $this->hasOne(Identitas::class, 'Assessment_id');
    }

    public function institusi()
    {
        return $this->belongsTo(Institusi::class, 'institution_id');
    }
}
