<?php

namespace App\Filament\Resources\PadreResource\Pages;

use App\Filament\Resources\PadreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePadre extends CreateRecord
{
    protected static string $resource = PadreResource::class;

    protected function afterCreate(): void
    {
        $this->record->assignRole('padre');
    }
}
