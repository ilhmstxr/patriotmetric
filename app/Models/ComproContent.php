<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ComproContent extends Model
{
    protected $table = 'compro_contents';

    protected $fillable = [
        'page', 'section', 'key', 'type', 'value', 'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Accessor/Mutator for value field.
     * GET: JSON decode for repeater type, plain value otherwise.
     * SET: JSON encode arrays, plain value otherwise.
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->type === 'repeater' ? json_decode($value, true) : $value,
            set: fn ($value) => is_array($value) ? json_encode($value) : $value,
        );
    }

    /**
     * Scope: filter by page.
     */
    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    /**
     * Scope: filter by section.
     */
    public function scopeForSection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope: only static (non-repeater) content.
     */
    public function scopeStatic($query)
    {
        return $query->where('type', '!=', 'repeater');
    }

    /**
     * Scope: only repeater content.
     */
    public function scopeRepeater($query)
    {
        return $query->where('type', 'repeater');
    }
}
