<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers;

use Crumbls\Subscriptions\Enums\Interval;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
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
            TextInput::make('name.' . app()->getLocale())
                ->label('Name')
                ->required()
                ->maxLength(150),

            TextInput::make('description.' . app()->getLocale())
                ->label('Description')
                ->maxLength(32768),

            TextInput::make('slug')
                ->maxLength(150)
                ->helperText('Auto-generated from name if left blank.'),

            TextInput::make('resettable_period')
                ->numeric()
                ->default(0)
                ->label('Reset Every'),

            \Filament\Forms\Components\Select::make('resettable_interval')
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
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->pivot?->value),

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
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('value')
                            ->required()
                            ->helperText('Numeric limit, or "true"/"false" for boolean features.'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
