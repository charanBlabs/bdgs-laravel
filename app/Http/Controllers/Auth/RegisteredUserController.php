<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /** @throws ValidationException */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],
        ]);

        $user = User::query()->create([
            'first_name' => $request->string('first_name'),
            'last_name' => $request->string('last_name'),
            'email' => $request->string('email'),
            'password' => $request->string('password'),
        ]);

        if ($request->filled('phone')) {
            $user->profile()->updateOrCreate(['user_id' => $user->id], ['phone' => $request->string('phone')]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect($this->redirectPath($user));
    }

    private function redirectPath(User $user): string
    {
        return $user->isAdmin() ? route('admin.dashboard', absolute: false) : route('dashboard', absolute: false);
    }
}
