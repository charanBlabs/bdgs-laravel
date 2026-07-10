<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsDataMeta extends Model
{
    protected $table = 'bdgs_data_meta';

    protected $fillable = [
        'post_id',
        'key',
        'value',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(BdgsDataPost::class, 'post_id');
    }
}
