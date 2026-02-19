<?php

namespace App\Filament\Resources\ElectionPromises\Pages;

use App\Filament\Resources\ElectionPromises\ElectionPromiseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageElectionPromises extends ManageRecords
{
    protected static string $resource = ElectionPromiseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
