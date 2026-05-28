<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    /** @use HasFactory<\Database\Factories\IdentitasFactory> */
    use HasFactory;

    protected $fillable = [
        'Assessment_id',
        'jml_mahasiswa',
        'jml_dosen',
        'jml_tendik',
        'jml_prodi',
        'jml_ukm',
        'jml_fakultas',
        'jml_ormawa',
        'jml_agama',
        'visi',
        'misi',
        'legal_documents',
        'is_verified',
        'admin_note',
    ];

    protected $casts = [
        'legal_documents' => 'array',
        'is_verified' => 'boolean',
    ];

    public function Assessment()
    {
        return $this->belongsTo(Assessment::class, 'Assessment_id');
    }

    public function agamas()
    {
        return $this->hasMany(Agama::class, 'identitas_id');
    }
}
