<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    //
    protected $table = 'agamas';

    protected $fillable = [
        'identitas_id',
        'agama',
        'jumlah'
    ];

    public $timestamps = false;

    public function identitas()
    {
        return $this->belongsTo(Identitas::class);
    }
}
