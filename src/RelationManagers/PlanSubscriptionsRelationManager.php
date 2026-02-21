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

    protected static ?string $title = 'Subscriptions';

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

            Select::make('plan_id')
                ->relationship('plan', 'name')
                ->required()
                ->searchable()
                ->preload(),

            DateTimePicker::make('trial_ends_at')
                ->label('Trial Ends At'),

            DateTimePicker::make('starts_at')
                ->label('Starts At'),

            DateTimePicker::make('ends_at')
                ->label('Ends At'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
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
            ->headerActions([
                CreateAction::make(),
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
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
