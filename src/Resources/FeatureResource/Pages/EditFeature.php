<?php

declare(strict_types=1);

namespace Crumbls\SubscriptionsFilament\Resources\FeatureResource\Pages;

use Crumbls\SubscriptionsFilament\Resources\FeatureResource\FeatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeature extends EditRecord
{
    protected static string $resource = FeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
