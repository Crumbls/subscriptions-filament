<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\FeatureResource\FeatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFeatures extends ListRecords
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
