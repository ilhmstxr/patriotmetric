<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class opsiJawaban extends Model
{
    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'pertanyaan_id',
        'opsi_jawaban',
        'value',
        'keterangan',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(pertanyaan::class, 'pertanyaan_id');
    }

    public function jawabanUser()
    {
        return $this->hasMany(pengumpulanJawaban::class, 'jawaban_id');
    }
}
