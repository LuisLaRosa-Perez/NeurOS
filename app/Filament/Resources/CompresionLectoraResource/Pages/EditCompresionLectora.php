<?php

namespace App\Filament\Resources\CompresionLectoraResource\Pages;

use App\Filament\Resources\CompresionLectoraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompresionLectora extends EditRecord
{
    protected static string $resource = CompresionLectoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
