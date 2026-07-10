<?php

namespace App\Providers;

use App\Models\BdgsCategory;
use App\Models\BdgsDataPost;
use App\Models\BdgsMedia;
use App\Models\BdgsUserData;
use App\Listeners\AuthEventSubscriber;
use App\Observers\ActivityLogObserver;
use App\Services\EmailService;
use App\Services\ReviewsService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->configureAuthMailNotifications();

        Gate::define('manage-content', fn ($user) => $user->canManageContent());

        RateLimiter::for('reviews-sync', function ($request) {
            return Limit::perMinute(300)->by($request->header('X-Api-Key') ?: $request->ip());
        });

        Event::subscribe(AuthEventSubscriber::class);

        BdgsDataPost::observe(ActivityLogObserver::class);
        BdgsCategory::observe(ActivityLogObserver::class);
        BdgsMedia::observe(ActivityLogObserver::class);
        BdgsUserData::observe(ActivityLogObserver::class);

        View::composer('*', function ($view) {
            if (! array_key_exists('reviewCount', $view->getData())) {
                $view->with('reviewCount', app(ReviewsService::class)->publishedCount());
            }
        });
    }

    private function configureAuthMailNotifications(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return $this->mailMessageFromTemplate('password-reset', [
                'first_name' => $notifiable->first_name ?? '',
                'full_name' => method_exists($notifiable, 'fullName') ? $notifiable->fullName() : '',
                'email' => $notifiable->getEmailForPasswordReset(),
                'reset_url' => $resetUrl,
            ], fn () => (new MailMessage)
                ->subject('Reset your password — '.config('app.name'))
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', $resetUrl)
                ->line('If you did not request a password reset, no further action is required.'));
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
            return $this->mailMessageFromTemplate('email-verification', [
                'first_name' => $notifiable->first_name ?? '',
                'full_name' => method_exists($notifiable, 'fullName') ? $notifiable->fullName() : '',
                'email' => $notifiable->getEmailForVerification(),
                'verification_url' => $url,
            ], fn () => (new MailMessage)
                ->subject('Verify your email — '.config('app.name'))
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $url)
                ->line('If you did not create an account, no further action is required.'));
        });
    }

    /** @param  callable(): MailMessage  $fallback */
    private function mailMessageFromTemplate(string $slug, array $variables, callable $fallback): MailMessage
    {
        $rendered = app(EmailService::class)->render($slug, $variables);

        if (! $rendered) {
            return $fallback();
        }

        return (new MailMessage)
            ->subject($rendered['subject'])
            ->view('mail.html-template', ['html' => $rendered['body_html']]);
    }
}
