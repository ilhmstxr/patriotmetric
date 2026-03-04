<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pertanyaan extends Model
{
    /** @use HasFactory<\Database\Factories\PertanyaanFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'teks_pertanyaan', 'tipe', 'opsi_jawaban'];

    protected $casts = [
        'opsi_jawaban' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'category_id');
    }

    public function jawaban()
    {
        return $this->hasMany(pengumpulan_jawaban::class, 'question_id');
    }
}
