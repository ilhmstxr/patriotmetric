<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pertanyaan extends Model
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
        //
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'category_id');
    }

    public function jawaban()
    {
        return $this->hasMany(pengumpulan_jawaban::class, 'pertanyaan_id');
    }

    public function opsiJawabans()
    {
        return $this->hasMany(opsiJawaban::class, 'pertanyaan_id');
    }
}
