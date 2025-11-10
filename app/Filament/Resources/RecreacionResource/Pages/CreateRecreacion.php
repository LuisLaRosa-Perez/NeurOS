<?php

namespace App\Filament\Resources\RecreacionResource\Pages;

use App\Filament\Resources\RecreacionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecreacion extends CreateRecord
{
    protected static string $resource = RecreacionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'recreacion';

        return $data;
    }
}
