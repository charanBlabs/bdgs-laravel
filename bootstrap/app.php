<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'reviews.sync' => \App\Http\Middleware\VerifyReviewsSyncToken::class,
            'inquiry.agent' => \App\Http\Middleware\VerifyInquiryAgentToken::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->append([
            \App\Http\Middleware\HandleRedirects::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        $middleware->throttleApi('60,1');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Too many registration attempts. Please wait a minute and try again.',
                    'errors' => [
                        'email' => ['Too many registration attempts. Please wait a minute and try again.'],
                    ],
                ], 429);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $status = $e->getStatusCode();
            if (in_array($status, [403, 404, 500, 503], true) && ! $request->expectsJson()) {
                $view = view()->exists("errors.{$status}") ? "errors.{$status}" : 'errors.generic';

                $safeMessages = [
                    403 => 'You are not authorized to access this page.',
                    404 => 'The page you are looking for could not be found.',
                    500 => 'Something went wrong. Please try again later.',
                    503 => 'We are currently performing maintenance. Please check back shortly.',
                ];

                return response()->view($view, [
                    'status' => $status,
                    'message' => $safeMessages[$status] ?? 'An error occurred.',
                ], $status);
            }
        });

        $exceptions->render(function (\InvalidArgumentException $e, $request) {
            if (str_contains($e->getMessage(), 'View [') && ! $request->expectsJson()) {
                return response()->view('errors.404', ['status' => 404, 'message' => 'The page you are looking for could not be found.'], 404);
            }
        });
    })->create();
