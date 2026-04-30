<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    /** @use HasFactory<\Database\Factories\PertanyaanFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'kode_pertanyaan',
        'teks_pertanyaan',
        'deskripsi',
        'kebutuhan_bukti',
        'tipe',
        'skor_maksimal'
    ];

    protected $casts = [
        'kebutuhan_bukti' => 'json',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function jawaban()
    {
        return $this->hasMany(PengumpulanJawaban::class, 'pertanyaan_id');
    }

    public function OpsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }
}
