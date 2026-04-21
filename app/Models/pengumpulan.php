<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengumpulan extends Model
{
    /** @use HasFactory<\Database\Factories\PengumpulanFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'reviewer_id', 'status', 'total_skor_sistem', 'total_skor_akhir'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function jawabans()
    {
        return $this->hasMany(pengumpulan_jawaban::class, 'submission_id');
    }

    public function identitas()
    {
        return $this->hasOne(Identitas::class, 'pengumpulan_id');
    }
}
