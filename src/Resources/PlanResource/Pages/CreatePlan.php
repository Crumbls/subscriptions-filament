<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\PlanResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\PlanResource\PlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlan extends CreateRecord
{
    protected static string $resource = PlanResource::class;
}
