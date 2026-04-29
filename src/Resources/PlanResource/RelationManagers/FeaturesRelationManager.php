<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\RelationManagers;

use Crumbls\SubscriptionsFilament\Resources\FeatureResource\Schemas\FeatureForm;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
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
            ...FeatureForm::components(),

            TextInput::make('value')
                ->label(__('subscriptions-filament::subscriptions-filament.relation_managers.features.fields.value'))
                ->required()
                ->helperText(__('subscriptions-filament::subscriptions-filament.relation_managers.features.fields.value_helper')),
        ]);
    }

    public function table(Table $table): Table
    {
        $pivotTable = config('subscriptions.tables.plan_features', 'plan_features');
        $pivotSortColumn = "{$pivotTable}.sort_order";

        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('subscriptions-filament::subscriptions-filament.relation_managers.features.columns.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pivot.value')
                    ->label(__('subscriptions-filament::subscriptions-filament.relation_managers.features.columns.value')),

                TextColumn::make('resettable_period')
                    ->label(__('subscriptions-filament::subscriptions-filament.relation_managers.features.columns.resets'))
                    ->formatStateUsing(function ($record): string {
                        if (! $record->resettable_period) {
                            return __('subscriptions-filament::subscriptions-filament.common.never');
                        }

                        return "{$record->resettable_period} {$record->resettable_interval?->value}(s)";
                    }),
            ])
            ->headerActions([
                CreateAction::make(),
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordTitle(fn ($record): string => $record->name)
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('value')
                            ->label(__('subscriptions-filament::subscriptions-filament.relation_managers.features.fields.value'))
                            ->required()
                            ->helperText(__('subscriptions-filament::subscriptions-filament.relation_managers.features.fields.value_helper')),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->reorderable($pivotSortColumn)
            ->defaultSort($pivotSortColumn);
    }
}
