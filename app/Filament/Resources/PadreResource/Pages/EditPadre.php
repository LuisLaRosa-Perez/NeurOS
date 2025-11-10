<?php

namespace App\Filament\Resources\PadreResource\Pages;

use App\Filament\Resources\PadreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPadre extends EditRecord
{
    protected static string $resource = PadreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
