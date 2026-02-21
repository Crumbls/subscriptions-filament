<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subscriber_type')
                    ->label('Subscriber Type')
                    ->formatStateUsing(fn (string $state) => class_basename($state))
                    ->sortable(),

                TextColumn::make('subscriber_id')
                    ->label('Subscriber ID')
                    ->sortable(),

                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->searchable(),

                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        if ($record->canceled()) {
                            return 'Canceled';
                        }
                        if ($record->ended()) {
                            return $record->onGracePeriod() ? 'Grace' : 'Ended';
                        }
                        if ($record->onTrial()) {
                            return 'Trial';
                        }

                        return 'Active';
                    })
                    ->color(fn (string $state) => match ($state) {
                        'Active' => 'success',
                        'Trial' => 'info',
                        'Grace' => 'warning',
                        'Canceled' => 'danger',
                        'Ended' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('canceled_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->relationship('plan', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalDescription('Cancel this subscription immediately?')
                    ->action(fn ($record) => $record->cancel(immediately: true))
                    ->visible(fn ($record) => $record->active() && ! $record->canceled()),

                Action::make('renew')
                    ->label('Renew')
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->renew())
                    ->visible(fn ($record) => $record->ended() && ! $record->canceled()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
