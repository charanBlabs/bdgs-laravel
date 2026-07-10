<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdgsRedirect extends Model
{
    protected $table = 'bdgs_redirects';

    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
