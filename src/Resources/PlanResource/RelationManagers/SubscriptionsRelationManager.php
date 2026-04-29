<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers;

use Closure;
use Crumbls\SubscriptionsFilament\SubscriptionsPlugin;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'subscriptions';

    public function form(Schema $schema): Schema
    {
        $plugin = SubscriptionsPlugin::get();

        $types = [];

        foreach ($plugin->getSubscriberTypes() as $class => $config) {
            $type = MorphToSelect\Type::make($class);

            $label = $config['label'] ?? null;

            if (is_string($label) && $label !== '') {
                $type = $type->titleAttribute($label);
            } elseif ($label instanceof Closure) {
                $type = $type->titleAttribute('id')
                    ->getOptionLabelFromRecordUsing($label);
            } else {
                $type = $type->titleAttribute('id');
            }

            $types[] = $type;
        }

        return $schema->components([
            $types === []
                ? TextInput::make('subscriber_type')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.subscriber_type'))
                    ->required()
                    ->helperText(__('subscriptions-filament::subscriptions-filament.common.no_subscriber_types'))
                    ->disabled()
                : MorphToSelect::make('subscriber')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.fields.subscriber'))
                    ->types($types)
                    ->searchable()
                    ->required(),

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
            ])
            ->filters(array_filter([
                $typeOptions === [] ? null : SelectFilter::make('subscriber_type')
                    ->label(__('subscriptions-filament::subscriptions-filament.subscription.columns.subscriber_type'))
                    ->options($typeOptions),
            ]))
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
