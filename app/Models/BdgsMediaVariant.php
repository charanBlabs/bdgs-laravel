<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsMediaVariant extends Model
{
    public $timestamps = false;

    protected $table = 'bdgs_media_variants';

    protected $fillable = [
        'media_id',
        'variant_name',
        'path',
        'width',
        'height',
        'size_bytes',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(BdgsMedia::class, 'media_id');
    }
}
