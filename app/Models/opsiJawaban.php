<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpsiJawaban extends Model
{
    /** @use HasFactory<\Database\Factories\OpsiJawabanFactory> */
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'pertanyaan_id',
        'opsi_jawaban',
        'value',
        'keterangan',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function jawabanUser()
    {
        return $this->hasMany(PengumpulanJawaban::class, 'jawaban_id');
    }
}
