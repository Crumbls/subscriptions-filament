<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\Schemas;

use Crumbls\Subscriptions\Enums\Interval;
use Crumbls\SubscriptionsFilament\Fields\CurrencyField;
use Crumbls\SubscriptionsFilament\Fields\MoneyField;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Section::make(__('subscriptions-filament::subscriptions-filament.plan.sections.details'))
                    ->columnSpan(['md' => 'full', 'lg' => 6])
                    ->schema([
                        TextInput::make('name.' . app()->getLocale())
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.name'))
                            ->required()
                            ->maxLength(150),

                        TextInput::make('description.' . app()->getLocale())
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.description'))
                            ->maxLength(32768)
                            ->columnSpanFull(),
                    ]),

                Section::make(__('subscriptions-filament::subscriptions-filament.plan.sections.status'))
                    ->columnSpan(['md' => 'full', 'lg' => 6])
                    ->schema([
                        Checkbox::make('is_active')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.is_active'))
                            ->default(true),

                        TextInput::make('active_subscribers_limit')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.active_subscribers_limit'))
                            ->numeric()
                            ->placeholder(__('subscriptions-filament::subscriptions-filament.common.unlimited')),

                        TextInput::make('sort_order')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.sort_order'))
                            ->numeric()
                            ->default(0),
                    ]),

                Section::make(__('subscriptions-filament::subscriptions-filament.plan.sections.pricing'))
                    ->columns(5)
                    ->columnSpanFull()
                    ->schema([
                        CurrencyField::make('currency')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.currency'))
                            ->default('USD')
                            ->required(),

                        MoneyField::make('price')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.price'))
                            ->required(),

                        MoneyField::make('signup_fee')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.signup_fee'))
                            ->required(),

                        TextInput::make('invoice_period')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.invoice_period'))
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1),

                        Select::make('invoice_interval')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.invoice_interval'))
                            ->options(Interval::class)
                            ->required()
                            ->default('month'),

                        TextInput::make('trial_period')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.trial_period'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Select::make('trial_interval')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.trial_interval'))
                            ->options(Interval::class)
                            ->default('day'),

                        TextInput::make('grace_period')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.grace_period'))
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        Select::make('grace_interval')
                            ->label(__('subscriptions-filament::subscriptions-filament.plan.fields.grace_interval'))
                            ->options(Interval::class)
                            ->default('day'),
                    ]),
            ]);
    }
}
