<?php

namespace App\Filament\Resources\MatematicasResource\Pages;

use App\Filament\Resources\MatematicasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatematicas extends EditRecord
{
    protected static string $resource = MatematicasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
