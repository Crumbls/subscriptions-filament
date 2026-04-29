<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament;

use Illuminate\Support\ServiceProvider;

class SubscriptionsFilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(
            __DIR__ . '/../lang',
            'subscriptions-filament',
        );

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../lang' => $this->app->langPath('vendor/subscriptions-filament'),
            ], 'subscriptions-filament-translations');
        }
    }
}
