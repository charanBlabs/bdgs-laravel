<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

Artisan::command('bdgs:optimize-production', function () {
    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');
    $this->call('event:cache');

    if (! File::exists(public_path('storage'))) {
        $this->call('storage:link');
    }

    $this->info('Production caches warmed and storage link verified.');
})->purpose('Cache config, routes, views, and events for production');

Artisan::command('bdgs:clear-caches', function () {
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
    $this->call('event:clear');
    $this->info('Application caches cleared.');
})->purpose('Clear all Laravel optimization caches');
