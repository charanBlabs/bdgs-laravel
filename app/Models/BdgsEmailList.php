<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdgsEmailList extends Model
{
    protected $table = 'bdgs_email_lists';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function subscribers(): HasMany
    {
        return $this->hasMany(BdgsEmailListSubscriber::class, 'list_id');
    }

    public function activeSubscribers(): HasMany
    {
        return $this->hasMany(BdgsEmailListSubscriber::class, 'list_id')
            ->where('status', 'subscribed');
    }
}
