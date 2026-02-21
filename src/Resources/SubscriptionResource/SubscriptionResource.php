<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource;

use BackedEnum;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages\ListSubscriptions;
use UnitEnum;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages\ViewSubscription;
use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Tables\SubscriptionsTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SubscriptionResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Subscriptions';

    protected static ?string $modelLabel = 'Subscription';

    protected static ?int $navigationSort = 2;

    public static function getModel(): string
    {
        return config('subscriptions.models.plan_subscription');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
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
