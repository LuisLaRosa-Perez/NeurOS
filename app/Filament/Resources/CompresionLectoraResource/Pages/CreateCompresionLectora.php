<?php

namespace App\Filament\Resources\CompresionLectoraResource\Pages;

use App\Filament\Resources\CompresionLectoraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Models\Task; // Added
use App\Events\ReadingTaskGenerated; // Added
use App\Events\ReadingTaskFailed; // Added

class CreateCompresionLectora extends CreateRecord
{
    protected static string $resource = CompresionLectoraResource::class;

    public $isGenerating = false; // Keep this as it might be used by the form actions

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category'] = 'compresion_lectora';

        return $data;
    }

    protected function getListeners(): array
    {
        return [
            "echo-private:user." . Auth::id() . ",.ReadingTaskGenerated" => 'onTaskGenerated', // Listen for broadcast event
            "echo-private:user." . Auth::id() . ",.ReadingTaskFailed" => 'onTaskFailed', // Listen for broadcast event
        ];
    }

    public function onTaskGenerated(\App\Models\Task $task, $userId) // Changed signature
    {
        if (Auth::id() !== $userId) {
            return;
        }

        $this->isGenerating = false; // Reset generating state

        Notification::make()
            ->title('Tarea Generada Correctamente')
            ->body('La tarea ha sido generada en segundo plano y guardada. Ahora puedes revisarla y editarla.')
            ->success()
            ->send();

        // Redirect to the edit page of the newly created task
        $this->redirect(CompresionLectoraResource::getUrl('edit', ['record' => $task->id]));
    }

    public function onTaskFailed($userId, $errorMessage)
    {
        if (Auth::id() !== $userId) {
            return;
        }

        $this->isGenerating = false; // Reset generating state

        Notification::make()
            ->title('Error al Generar Tarea')
            ->body('No se pudo generar la tarea. Detalles: ' . $errorMessage)
            ->danger()
            ->send();
    }
} // Added missing closing brace for the class
