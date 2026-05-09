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
        'domain_email',
        'logo_url'
    ];

    protected $appends = ['logo_url_full'];

    public function getLogoUrlFullAttribute(): ?string
    {
        if (empty($this->logo_url)) {
            return null;
        }

        // Jika sudah berupa URL lengkap
        if (str_starts_with($this->logo_url, 'http://') || str_starts_with($this->logo_url, 'https://')) {
            return $this->logo_url;
        }

        // Jika sudah berupa path storage, gunakan langsung
        if (str_starts_with($this->logo_url, '/storage/') || str_starts_with($this->logo_url, 'storage/')) {
            return asset($this->logo_url);
        }

        // Default: prefix dengan assets/images/
        return asset('assets/images/' . $this->logo_url);
    }

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
