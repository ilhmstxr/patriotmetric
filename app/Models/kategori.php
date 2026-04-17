<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory;

    protected $fillable = ['nama_kategori', 'deskripsi', 'bobot_presentase'];

    public function pertanyaans()
    {
        return $this->hasMany(pertanyaan::class, 'category_id');
    }

    public function jawabans()
    {
        return $this->hasManyThrough(pengumpulan_jawaban::class, pertanyaan::class, 'category_id', 'question_id');
    }
}
