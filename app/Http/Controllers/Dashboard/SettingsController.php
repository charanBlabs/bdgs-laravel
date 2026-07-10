<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function profile(): View
    {
        $user = $this->authUser()->load('profile');

        return view('dashboard.settings.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $this->authUser();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'company' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:500'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
        ];

        if ($user->email !== $validated['email']) {
            $updateData['email_verified_at'] = null;
        }

        $user->update($updateData);

        if ($user->wasChanged('email')) {
            $user->sendEmailVerificationNotification();
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            collect($validated)->except(['first_name', 'last_name', 'email'])->all()
        );

        return back()->with('status', 'Profile updated.');
    }

    public function password(): View
    {
        return view('dashboard.settings.password');
    }

    public function photo(): View
    {
        $user = $this->authUser()->load('profile.avatar', 'profile.logo');

        return view('dashboard.settings.photo', compact('user'));
    }

    public function updatePhoto(Request $request, MediaService $mediaService): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'image', 'max:5120'],
            'logo' => ['nullable', 'image', 'max:5120'],
        ]);

        $user = $this->authUser();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        if ($request->hasFile('avatar')) {
            $media = $mediaService->upload($request->file('avatar'), $user->fullName().' avatar');
            $profile->update(['avatar_media_id' => $media->id]);
        }

        if ($request->hasFile('logo')) {
            $media = $mediaService->upload($request->file('logo'), ($profile->company ?? 'Company').' logo');
            $profile->update(['logo_media_id' => $media->id]);
        }

        return back()->with('status', 'Photos updated.');
    }
}
