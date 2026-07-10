<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsCategory extends Model
{
    protected $table = 'bdgs_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'post_type_id',
        'sort_order',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function postType(): BelongsTo
    {
        return $this->belongsTo(BdgsDataType::class, 'post_type_id');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BdgsDataPost::class, 'bdgs_rel_categories', 'category_id', 'post_id');
    }
}
