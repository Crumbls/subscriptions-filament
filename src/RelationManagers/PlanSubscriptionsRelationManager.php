<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Drop-in relation manager for any resource whose model uses HasPlanSubscriptions.
 *
 * Usage in your resource:
 *
 *     use Crumbls\SubscriptionsFilament\RelationManagers\PlanSubscriptionsRelationManager;
 *
 *     public static function getRelations(): array
 *     {
 *         return [
 *             PlanSubscriptionsRelationManager::class,
 *         ];
 *     }
 */
class PlanSubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'planSubscriptions';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('subscriptions-filament::subscriptions-filament.relation_managers.subscriptions.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name.' . app()->getLocale())
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.name'))
                ->required()
                ->maxLength(150),

            TextInput::make('description.' . app()->getLocale())
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.description'))
                ->maxLength(32768),

            Select::make('plan_id')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.plan'))
                ->relationship('plan', 'name')
                ->required()
                ->searchable()
                ->preload(),

            DateTimePicker::make('trial_ends_at')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.trial_ends_at')),

            DateTimePicker::make('starts_at')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.starts_at')),

            DateTimePicker::make('ends_at')
                ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.ends_at')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.plan'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.name'))
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
            ->filters([
                SelectFilter::make('plan')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.plan'))
                    ->relationship('plan', 'name'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
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
                DeleteAction::make(),
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
