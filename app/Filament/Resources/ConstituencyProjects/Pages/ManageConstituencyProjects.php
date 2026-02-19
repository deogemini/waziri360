<?php

namespace App\Filament\Resources\ConstituencyProjects\Pages;

use App\Filament\Resources\ConstituencyProjects\ConstituencyProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConstituencyProjects extends ManageRecords
{
    protected static string $resource = ConstituencyProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
