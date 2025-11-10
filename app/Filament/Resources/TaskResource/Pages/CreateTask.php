<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    public function mount(): void
    {
        parent::mount();

        if (request()->has(['name', 'description'])) {
            $this->form->fill([
                'name' => request('name'),
                'description' => request('description'),
            ]);
        }
    }
}
