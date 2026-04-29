<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource\Schemas;

use Crumbls\Subscriptions\Enums\Interval;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(self::components());
    }

    /**
     * @return array<int, \Filament\Schemas\Components\Component>
     */
    public static function components(): array
    {
        return [
            Section::make(__('subscriptions-filament::subscriptions-filament.feature.sections.details'))
                ->schema([
                    TextInput::make('name.' . app()->getLocale())
                        ->label(__('subscriptions-filament::subscriptions-filament.feature.fields.name'))
                        ->required()
                        ->maxLength(150),

                    TextInput::make('description.' . app()->getLocale())
                        ->label(__('subscriptions-filament::subscriptions-filament.feature.fields.description'))
                        ->maxLength(32768),

                    TextInput::make('slug')
                        ->label(__('subscriptions-filament::subscriptions-filament.feature.fields.slug'))
                        ->maxLength(150)
                        ->helperText(__('subscriptions-filament::subscriptions-filament.feature.fields.slug_helper')),
                ]),

            Section::make(__('subscriptions-filament::subscriptions-filament.feature.sections.reset_cycle'))
                ->columns(2)
                ->schema([
                    TextInput::make('resettable_period')
                        ->label(__('subscriptions-filament::subscriptions-filament.feature.fields.resettable_period'))
                        ->numeric()
                        ->default(0),

                    Select::make('resettable_interval')
                        ->label(__('subscriptions-filament::subscriptions-filament.feature.fields.resettable_interval'))
                        ->options(Interval::class)
                        ->default('month'),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
