<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
