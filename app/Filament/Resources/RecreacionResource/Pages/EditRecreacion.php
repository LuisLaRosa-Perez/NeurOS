<?php

namespace App\Filament\Resources\RecreacionResource\Pages;

use App\Filament\Resources\RecreacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecreacion extends EditRecord
{
    protected static string $resource = RecreacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
