<?php

namespace App\Filament\Resources\MatematicasResource\Pages;

use App\Filament\Resources\MatematicasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatematicas extends ListRecords
{
    protected static string $resource = MatematicasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
