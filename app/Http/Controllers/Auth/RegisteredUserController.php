<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function thankYou(Request $request): View|RedirectResponse
    {
        if (! $request->session()->pull('registration_complete') && ! $request->user()) {
            return redirect()->route('register');
        }

        $user = $request->user();

        return view('auth.register-thank-you', [
            'user' => $user,
            'needsEmailVerification' => false,
        ]);
    }

    /** @throws ValidationException */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => Str::lower(trim((string) $request->input('email', ''))),
            'first_name' => trim((string) $request->input('first_name', '')),
            'last_name' => trim((string) $request->input('last_name', '')),
            'phone' => trim((string) $request->input('phone', '')) ?: null,
        ]);

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],
        ]);

        $user = User::query()->create([
            'first_name' => $request->string('first_name')->toString(),
            'last_name' => $request->string('last_name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
            'is_active' => true,
        ]);

        if ($request->filled('phone')) {
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                ['phone' => $request->string('phone')->toString()]
            );
        }

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('registration_complete', true);

        return redirect()->route('register.thank-you');
    }
}
