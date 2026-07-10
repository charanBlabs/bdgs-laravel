<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdgsEmailTemplate extends Model
{
    protected $table = 'bdgs_email_templates';

    protected $fillable = [
        'slug',
        'name',
        'subject',
        'body_html',
        'body_text',
        'variables',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
