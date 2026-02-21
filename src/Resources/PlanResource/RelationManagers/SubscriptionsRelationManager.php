<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

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

            TextInput::make('subscriber_type')
                ->required()
                ->helperText('Fully qualified model class (e.g. App\\Models\\User)'),

            TextInput::make('subscriber_id')
                ->required()
                ->numeric(),

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
                TextColumn::make('subscriber_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state) => class_basename($state)),

                TextColumn::make('subscriber_id')
                    ->label('Subscriber ID'),

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
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
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
