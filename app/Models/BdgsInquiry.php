<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdgsInquiry extends Model
{
    protected $table = 'bdgs_inquiries';

    protected $primaryKey = 'inquiry_id';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'directory_url',
        'need',
        'message',
        'source',
        'status',
        'admin_reply',
        'ip_address',
        'user_agent',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }
}
