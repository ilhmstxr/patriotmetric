<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
