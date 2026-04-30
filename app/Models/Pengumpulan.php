<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumpulan extends Model
{
    /** @use HasFactory<\Database\Factories\PengumpulanFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'reviewer_id', 'institution_id', 'tahun_periode', 'status', 'total_skor_sistem', 'total_skor_akhir', 'nama_pic', 'jabatan_pic', 'no_hp_pic'];

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
        return $this->hasMany(PengumpulanJawaban::class, 'submission_id');
    }

    public function identitas()
    {
        return $this->hasOne(Identitas::class, 'pengumpulan_id');
    }

    public function institusi()
    {
        return $this->belongsTo(Institusi::class, 'institution_id');
    }
}
