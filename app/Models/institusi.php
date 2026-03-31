<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institusi extends Model
{
    /** @use HasFactory<\Database\Factories\InstitusiFactory> */
    use HasFactory;

    protected $table = 'institusis';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_institusi',
        'jenis_institusi',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'institution_id');
    }
}
