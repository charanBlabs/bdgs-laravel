<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function authUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }
}
