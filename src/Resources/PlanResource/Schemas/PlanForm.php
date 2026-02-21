<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\Schemas;

use Crumbls\Subscriptions\Enums\Interval;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->columnSpanFull()
                ->schema([
                    Grid::make(1)
                        ->columnSpan(2)
                        ->schema([
                            Section::make('Plan Details')
                                ->schema([
                                    TextInput::make('name.' . app()->getLocale())
                                        ->label('Name')
                                        ->required()
                                        ->maxLength(150),

                                    TextInput::make('description.' . app()->getLocale())
                                        ->label('Description')
                                        ->maxLength(32768)
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Pricing')
                                ->columns(3)
                                ->schema([
                                    TextInput::make('price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('$')
                                        ->default(0),

                                    TextInput::make('signup_fee')
                                        ->numeric()
                                        ->required()
                                        ->prefix('$')
                                        ->default(0),

                                    Select::make('currency')
                                        ->options([
                                            'USD' => 'USD',
                                            'EUR' => 'EUR',
                                            'GBP' => 'GBP',
                                            'CAD' => 'CAD',
                                            'AUD' => 'AUD',
                                        ])
                                        ->default('USD')
                                        ->required(),
                                ]),

                            Section::make('Billing Cycle')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('invoice_period')
                                        ->numeric()
                                        ->required()
                                        ->default(1)
                                        ->minValue(1),

                                    Select::make('invoice_interval')
                                        ->options(Interval::class)
                                        ->required()
                                        ->default('month'),
                                ]),
                        ]),

                    Grid::make(1)
                        ->columnSpan(1)
                        ->schema([
                            Section::make('Status')
                                ->schema([
                                    Checkbox::make('is_active')
                                        ->label('Active')
                                        ->default(true),

                                    TextInput::make('active_subscribers_limit')
                                        ->label('Subscriber Limit')
                                        ->numeric()
                                        ->placeholder('Unlimited')
                                        ->helperText('Leave blank for no limit.'),

                                    TextInput::make('sort_order')
                                        ->numeric()
                                        ->default(0),
                                ]),

                            Section::make('Trial Period')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('trial_period')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0),

                                    Select::make('trial_interval')
                                        ->options(Interval::class)
                                        ->default('day'),
                                ])
                                ->collapsible()
                                ->collapsed(),

                            Section::make('Grace Period')
                                ->columns(2)
                                ->schema([
                                    TextInput::make('grace_period')
                                        ->numeric()
                                        ->default(0)
                                        ->minValue(0),

                                    Select::make('grace_interval')
                                        ->options(Interval::class)
                                        ->default('day'),
                                ])
                                ->collapsible()
                                ->collapsed(),
                        ]),
                ]),
        ]);
    }
}
