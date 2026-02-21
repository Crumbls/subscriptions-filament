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
        return $schema->components([
            Section::make('Feature Details')
                ->schema([
                    TextInput::make('name.' . app()->getLocale())
                        ->label('Name')
                        ->required()
                        ->maxLength(150),

                    TextInput::make('description.' . app()->getLocale())
                        ->label('Description')
                        ->maxLength(32768),

                    TextInput::make('slug')
                        ->maxLength(150)
                        ->helperText('Auto-generated from name if left blank.'),
                ]),

            Section::make('Reset Cycle')
                ->columns(2)
                ->schema([
                    TextInput::make('resettable_period')
                        ->numeric()
                        ->default(0)
                        ->label('Reset Every'),

                    Select::make('resettable_interval')
                        ->options(Interval::class)
                        ->default('month')
                        ->label('Reset Interval'),
                ])
                ->collapsible()
                ->collapsed(),

            Section::make('Display')
                ->schema([
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }
}
