<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource;

use BackedEnum;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\CreatePlan;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\EditPlan;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages\ListPlans;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers\FeaturesRelationManager;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers\SubscriptionsRelationManager;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Schemas\PlanForm;
use Crumbls\SubscriptionsFilament\Resources\PlanResource\Tables\PlansTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlanResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getModel(): string
    {
        return config('subscriptions.models.plan');
    }

    public static function getModelLabel(): string
    {
        return __('subscriptions-filament::subscriptions-filament.resources.plan.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('subscriptions-filament::subscriptions-filament.resources.plan.plural_label');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('subscriptions-filament::subscriptions-filament.navigation.group');
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
            SubscriptionsRelationManager::class,
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
