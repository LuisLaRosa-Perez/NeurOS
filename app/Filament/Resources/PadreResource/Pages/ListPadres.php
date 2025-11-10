<?php

namespace App\Filament\Resources\PadreResource\Pages;

use App\Filament\Resources\PadreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPadres extends ListRecords
{
    protected static string $resource = PadreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
