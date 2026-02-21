<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource;

use BackedEnum;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages\CreateFeature;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages\EditFeature;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages\ListFeatures;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Schemas\FeatureForm;
use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Tables\FeaturesTable;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FeatureResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Subscriptions';

    protected static ?string $modelLabel = 'Feature';

    protected static ?int $navigationSort = 3;

    public static function getModel(): string
    {
        return config('subscriptions.models.feature');
    }

    public static function form(Schema $schema): Schema
    {
        return FeatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FeaturesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeatures::route('/'),
            'create' => CreateFeature::route('/create'),
            'edit' => EditFeature::route('/{record}/edit'),
        ];
    }
}
