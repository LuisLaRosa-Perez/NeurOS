<?php

namespace App\Filament\Resources\PsicologoResource\Pages;

use App\Filament\Resources\PsicologoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPsicologos extends ListRecords
{
    protected static string $resource = PsicologoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
