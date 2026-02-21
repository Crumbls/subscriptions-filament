<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers;

use Crumbls\Subscriptions\Enums\Interval;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeaturesRelationManager extends RelationManager
{
    protected static string $relationship = 'features';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(150),

            TextInput::make('slug')
                ->required()
                ->maxLength(150)
                ->helperText('Used to reference this feature in code.'),

            TextInput::make('value')
                ->required()
                ->helperText('Numeric limit, or "true"/"false" for boolean features.'),

            TextInput::make('resettable_period')
                ->numeric()
                ->default(0)
                ->label('Reset Every'),

            Select::make('resettable_interval')
                ->options(Interval::class)
                ->default('month')
                ->label('Reset Interval'),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('value')
                    ->badge(),

                TextColumn::make('resettable_period')
                    ->formatStateUsing(function ($record) {
                        if (! $record->resettable_period) {
                            return 'Never';
                        }

                        return "{$record->resettable_period} {$record->resettable_interval?->value}(s)";
                    })
                    ->label('Resets'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
