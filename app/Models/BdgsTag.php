<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BdgsTag extends Model
{
    public $timestamps = false;

    protected $table = 'bdgs_tags';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(BdgsDataPost::class, 'bdgs_rel_tags', 'tag_id', 'post_id');
    }
}
