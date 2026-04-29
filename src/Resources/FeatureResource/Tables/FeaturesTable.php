<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FeaturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('subscriptions-filament::subscriptions-filament.feature.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('subscriptions-filament::subscriptions-filament.feature.columns.slug'))
                    ->searchable(),

                TextColumn::make('plans_count')
                    ->counts('plans')
                    ->label(__('subscriptions-filament::subscriptions-filament.feature.columns.plans'))
                    ->sortable(),

                TextColumn::make('resettable_period')
                    ->label(__('subscriptions-filament::subscriptions-filament.feature.columns.resets'))
                    ->formatStateUsing(function ($record): string {
                        if (! $record->resettable_period) {
                            return __('subscriptions-filament::subscriptions-filament.common.never');
                        }

                        return "{$record->resettable_period} {$record->resettable_interval?->value}(s)";
                    }),

                TextColumn::make('created_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.feature.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
