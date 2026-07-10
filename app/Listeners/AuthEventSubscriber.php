<?php

namespace App\Listeners;

use App\Models\BdgsRole;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\EmailService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;

class AuthEventSubscriber
{
    public function __construct(
        private ActivityLogService $activityLog,
        private EmailService $emailService,
    ) {}

    public function handleLogin(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $event->user->recordSuccessfulLogin(request()->ip());

        $this->activityLog->log('login', $event->user, userId: $event->user->id);
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user instanceof User) {
            $this->activityLog->log('logout', $event->user, userId: $event->user->id);
        }
    }

    public function handleRegistered(Registered $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        $event->user->profile()->firstOrCreate(['user_id' => $event->user->id]);

        $userRole = BdgsRole::query()->where('name', 'user')->first();
        if ($userRole) {
            $event->user->roles()->syncWithoutDetaching([$userRole->id]);
        }

        $this->emailService->sendToUser($event->user, 'welcome', [
            'site_name' => config('app.name'),
            'dashboard_url' => url('/dashboard'),
        ]);

        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            $this->emailService->send('registration-admin', $adminEmail, [
                'first_name' => $event->user->first_name,
                'full_name' => $event->user->fullName(),
                'email' => $event->user->email,
                'site_name' => config('app.name'),
            ]);
        }

        $this->activityLog->log('created', $event->user, ['type' => 'registration'], $event->user->id);
    }

    /** @return array<string, string> */
    public function subscribe(): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Registered::class => 'handleRegistered',
        ];
    }
}
