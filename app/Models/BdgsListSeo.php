<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BdgsListSeo extends Model
{
    protected $table = 'bdgs_list_seo';

    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image_media_id',
        'canonical_url',
        'robots',
        'schema_markup',
    ];

    protected function casts(): array
    {
        return [
            'schema_markup' => 'array',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(BdgsMedia::class, 'og_image_media_id');
    }
}
