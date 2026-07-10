<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdgsUserData extends Model
{
    protected $table = 'bdgs_user_data';

    protected $fillable = [
        'user_id',
        'company',
        'phone',
        'position',
        'website',
        'bio',
        'avatar_media_id',
        'logo_media_id',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'timezone',
        'wallet_balance',
        'preferences',
        'additional_fields',
    ];

    protected function casts(): array
    {
        return [
            'wallet_balance' => 'decimal:2',
            'preferences' => 'array',
            'additional_fields' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(BdgsMedia::class, 'avatar_media_id');
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(BdgsMedia::class, 'logo_media_id');
    }
}
