<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\FeatureResource\FeatureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeature extends CreateRecord
{
    protected static string $resource = FeatureResource::class;
}
