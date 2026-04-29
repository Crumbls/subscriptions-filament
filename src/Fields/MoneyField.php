<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Fields;

use Closure;
use Crumbls\Subscriptions\Services\CurrencyService;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class MoneyField extends TextInput
{
    protected string|Closure $currencyField = 'currency';

    protected string $defaultCurrency = 'USD';

    public function currencyField(string|Closure $field): static
    {
        $this->currencyField = $field;

        return $this;
    }

    public function defaultCurrency(string $code): static
    {
        $this->defaultCurrency = strtoupper($code);

        return $this;
    }

    public function getCurrencyFieldName(): string
    {
        return (string) $this->evaluate($this->currencyField);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric()
            ->default(0)
            ->minValue(0)
            ->prefix(fn (Get $get): string => $this->service()->symbol($this->resolveCurrency($get)))
            ->step(fn (Get $get): float|int => $this->service()->step($this->resolveCurrency($get)))
            ->formatStateUsing(
                fn ($state, Get $get): string => $this->service()->fromMinor($state, $this->resolveCurrency($get)),
            )
            ->dehydrateStateUsing(
                fn ($state, Get $get): int => $this->service()->toMinor($state, $this->resolveCurrency($get)),
            );
    }

    protected function resolveCurrency(Get $get): string
    {
        $value = $get($this->getCurrencyFieldName());

        return is_string($value) && $value !== '' ? strtoupper($value) : $this->defaultCurrency;
    }

    protected function service(): CurrencyService
    {
        return app(CurrencyService::class);
    }
}
