<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanCms extends Model
{
    /** @use HasFactory<\Database\Factories\PengaturanCmsFactory> */
    use HasFactory;
    
    protected $table = "pengaturan_cms";
    protected $fillable = ['key', 'value'];
}
