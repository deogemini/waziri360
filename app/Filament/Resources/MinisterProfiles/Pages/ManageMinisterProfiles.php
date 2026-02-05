<?php

namespace App\Filament\Resources\MinisterProfiles\Pages;

use App\Filament\Resources\MinisterProfiles\MinisterProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMinisterProfiles extends ManageRecords
{
    protected static string $resource = MinisterProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
