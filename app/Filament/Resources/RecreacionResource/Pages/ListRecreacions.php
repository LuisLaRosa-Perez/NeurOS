<?php

namespace App\Filament\Resources\RecreacionResource\Pages;

use App\Filament\Resources\RecreacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecreacions extends ListRecords
{
    protected static string $resource = RecreacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
