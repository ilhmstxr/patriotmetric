<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory;

    protected $fillable = ['nama_kategori', 'deskripsi', 'bobot_presentase'];

    public function pertanyaans()
    {
        return $this->hasMany(Pertanyaan::class, 'category_id');
    }

    public function jawabans()
    {
        return $this->hasManyThrough(PengumpulanJawaban::class, Pertanyaan::class, 'category_id', 'question_id');
    }
}
