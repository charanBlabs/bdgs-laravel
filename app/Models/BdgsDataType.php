<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsDataType extends Model
{
    protected $table = 'bdgs_data_types';

    protected $fillable = [
        'slug',
        'name',
        'name_plural',
        'description',
        'icon',
        'supports',
        'meta_schema',
        'is_active',
        'sort_order',
    ];

    public function pluralLabel(): string
    {
        return $this->name_plural ?: \Illuminate\Support\Str::plural($this->name);
    }

    /** Public URL prefix for this type (e.g. solution → /solutions). */
    public function publicBasePath(): string
    {
        return '/'.match ($this->slug) {
            'solution' => 'solutions',
            'blog' => 'blog',
            'tool' => 'tools',
            'theme' => 'themes',
            'event' => 'events',
            default => \Illuminate\Support\Str::plural($this->slug),
        };
    }

    protected function casts(): array
    {
        return [
            'supports' => 'array',
            'meta_schema' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(BdgsDataPost::class, 'post_type_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(BdgsCategory::class, 'post_type_id');
    }
}
