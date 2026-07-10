<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BdgsEmailListSubscriber extends Model
{
    protected $table = 'bdgs_email_list_subscribers';

    protected $fillable = [
        'list_id',
        'user_id',
        'email',
        'name',
        'status',
        'source',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $subscriber) {
            if (! $subscriber->unsubscribe_token) {
                $subscriber->unsubscribe_token = Str::random(64);
            }
            if (! $subscriber->subscribed_at) {
                $subscriber->subscribed_at = now();
            }
        });
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(BdgsEmailList::class, 'list_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }
}
