<?php

namespace App\Filament\Resources\PsicologoResource\Pages;

use App\Filament\Resources\PsicologoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePsicologo extends CreateRecord
{
    protected static string $resource = PsicologoResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = parent::handleRecordCreation($data);
        $user->assignRole('psicologo');
        return $user;
    }
}
