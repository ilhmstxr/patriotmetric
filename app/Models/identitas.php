<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    /** @use HasFactory<\Database\Factories\IdentitasFactory> */
    use HasFactory;

    protected $fillable = [
        'pengumpulan_id',
        'jml_mahasiswa',
        'jml_dosen',
        'jml_tendik',
        'jml_prodi',
        'jml_ukm',
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

    public function pengumpulan()
    {
        return $this->belongsTo(Pengumpulan::class, 'pengumpulan_id');
    }
}
