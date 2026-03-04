<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengaturan_cms extends Model
{
    /** @use HasFactory<\Database\Factories\PengaturanCmsFactory> */
    use HasFactory;

    protected $fillable = ['key', 'value'];
}
