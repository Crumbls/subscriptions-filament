<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Fields;

use Crumbls\Subscriptions\Services\CurrencyService;
use Filament\Forms\Components\Select;

class CurrencyField extends Select
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->live()
            ->searchable()
            ->native(false)
            ->options(fn (): array => array_column(
                app(CurrencyService::class)->getCurrencies(),
                'label',
                'code',
            ));
    }
}
