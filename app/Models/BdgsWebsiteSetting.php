<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BdgsWebsiteSetting extends Model
{
    protected $table = 'bdgs_website_settings';

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];
}
