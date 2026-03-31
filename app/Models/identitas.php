<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identitas extends Model
{
    /** @use HasFactory<\Database\Factories\IdentitasFactory> */
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'jml_mahasiswa',
        'jml_dosen',
        'jml_tendik',
        'jml_prodi',
        'jml_ukm',
        'legal_documents',
        'is_verified',
        'admin_note',
    ];

    protected $casts = [
        'legal_documents' => 'array',
        'is_verified' => 'boolean',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class, 'assessment_id');
    }
}
