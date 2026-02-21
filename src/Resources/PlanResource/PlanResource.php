<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource;

use BackedEnum;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\CreatePlan;
use UnitEnum;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\EditPlan;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\ListPlans;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers\FeaturesRelationManager;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Schemas\PlanForm;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Tables\PlansTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Subscriptions';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string
    {
        return config('subscriptions.models.plan');
    }

    public static function form(Schema $schema): Schema
    {
        return PlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FeaturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit' => EditPlan::route('/{record}/edit'),
        ];
    }
}
