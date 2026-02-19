<?php

namespace App\Filament\Resources\ConstituencyActivities\Pages;

use App\Filament\Resources\ConstituencyActivities\ConstituencyActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConstituencyActivities extends ManageRecords
{
    protected static string $resource = ConstituencyActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
