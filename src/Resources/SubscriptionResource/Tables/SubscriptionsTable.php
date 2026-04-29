<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Tables;

use Crumbls\SubscriptionsFilament\SubscriptionsPlugin;
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
        $plugin = SubscriptionsPlugin::get();
        $typeOptions = $plugin->getSubscriberTypeOptions();

        return $table
            ->columns([
                TextColumn::make('subscriber')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.subscriber'))
                    ->state(fn ($record): string => $plugin->resolveSubscriberLabel($record->subscriber))
                    ->icon(fn ($record): ?string => $plugin->resolveSubscriberIcon($record->subscriber_type))
                    ->url(fn ($record): ?string => $plugin->resolveSubscriberUrl($record->subscriber))
                    ->description(fn ($record): string => $plugin->resolveTypeLabel($record->subscriber_type)),

                TextColumn::make('plan.name')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.plan'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.slug'))
                    ->searchable(),

                TextColumn::make('starts_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.starts_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.ends_at'))
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('status')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.status'))
                    ->badge()
                    ->getStateUsing(fn ($record): string => self::resolveStatusKey($record))
                    ->formatStateUsing(fn (string $state): string => __("subscriptions-filament::subscriptions-filament.subscription.statuses.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trial' => 'info',
                        'grace' => 'warning',
                        'canceled' => 'danger',
                        'ended' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('canceled_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.canceled_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(array_filter([
                SelectFilter::make('plan')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.plan'))
                    ->relationship('plan', 'name'),

                $typeOptions === [] ? null : SelectFilter::make('subscriber_type')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.subscriber_type'))
                    ->options($typeOptions),
            ]))
            ->recordActions([
                ViewAction::make(),
                Action::make('cancel')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.actions.cancel.label'))
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalDescription(__('subscriptions-filament::subscriptions-filament.subscription.actions.cancel.modal_description'))
                    ->action(fn ($record) => $record->cancel(immediately: true))
                    ->visible(fn ($record): bool => $record->active() && ! $record->canceled()),

                Action::make('renew')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.actions.renew.label'))
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->renew())
                    ->visible(fn ($record): bool => $record->ended() && ! $record->canceled()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function resolveStatusKey($record): string
    {
        if ($record->canceled()) {
            return 'canceled';
        }

        if ($record->ended()) {
            return $record->onGracePeriod() ? 'grace' : 'ended';
        }

        if ($record->onTrial()) {
            return 'trial';
        }

        return 'active';
    }
}
