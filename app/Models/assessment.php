<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    /** @use HasFactory<\Database\Factories\AssessmentFactory> */
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'user_id',
        'nama_pic',
        'jabatan_pic',
        'no_hp_pic',
        'tahun_periode',
        'status',
    ];

    public function institusi()
    {
        return $this->belongsTo(Institusi::class, 'institution_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function identitas()
    {
        return $this->hasOne(Identitas::class, 'assessment_id');
    }
}
