<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BdgsRole extends Model
{
    protected $table = 'bdgs_roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bdgs_role_user', 'role_id', 'user_id');
    }
}
