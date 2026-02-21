<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\SubscriptionResource\SubscriptionResource;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;
}
