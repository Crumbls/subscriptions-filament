<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.slug'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('price')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.price'))
                    ->formatStateUsing(fn ($record): string => $record->formattedPrice())
                    ->sortable(),

                TextColumn::make('invoice_interval')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.billing_cycle'))
                    ->formatStateUsing(function ($record): string {
                        $interval = $record->invoice_interval?->value;

                        if ($interval === null) {
                            return '—';
                        }

                        return "{$record->invoice_period} {$interval}(s)";
                    }),

                TextColumn::make('subscriptions_count')
                    ->counts('subscriptions')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.subscribers'))
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.active'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('subscriptions-filament::subscriptions-filament.plan.filters.active')),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
