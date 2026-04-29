<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource;

use BackedEnum;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages\ListSubscriptions;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages\ViewSubscription;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Tables\SubscriptionsTable;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SubscriptionResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 2;

    public static function getModel(): string
    {
        return config('subscriptions.models.plan_subscription');
    }

    public static function getModelLabel(): string
    {
        return __('subscriptions-filament::subscriptions-filament.resources.subscription.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('subscriptions-filament::subscriptions-filament.resources.subscription.plural_label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('subscriptions-filament::subscriptions-filament.navigation.group');
    }

    public static function table(Table $table): Table
    {
        return SubscriptionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptions::route('/'),
            'view' => ViewSubscription::route('/{record}'),
        ];
    }
}
