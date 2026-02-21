<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament;

use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

class SubscriptionsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'subscriptions';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            PlanResource::class,
            SubscriptionResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static;
    }
}
