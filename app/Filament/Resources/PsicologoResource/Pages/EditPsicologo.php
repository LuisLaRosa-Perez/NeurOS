<?php

namespace App\Filament\Resources\PsicologoResource\Pages;

use App\Filament\Resources\PsicologoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPsicologo extends EditRecord
{
    protected static string $resource = PsicologoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
