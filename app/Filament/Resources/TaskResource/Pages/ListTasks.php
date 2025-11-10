<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_ai')
                ->label('Crear Tarea con IA')
                ->icon('heroicon-o-sparkles')
                ->form([
                    \Filament\Forms\Components\TextInput::make('tema')
                        ->label('Tema a crear')
                        ->required(),
                    \Filament\Forms\Components\Select::make('edad')
                        ->label('Edad')
                        ->options([
                            '7' => '7 años',
                            '8' => '8 años',
                            '9' => '9 años',
                            '10' => '10 años',
                        ])
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('curso')
                        ->label('Curso')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $apiKey = env('GEMINI_API_KEY');
                    if (!$apiKey) {
                        Notification::make()
                            ->title('Error de Configuración')
                            ->body('La clave de API de Gemini no está configurada en el archivo .env.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $prompt = "Actúa como un profesor de primaria. Crea una actividad de aprendizaje sobre el tema '{$data['tema']}' para un niño de {$data['edad']} años del curso '{$data['curso']}'. La actividad debe incluir varios problemas o ejercicios listos para resolver. La respuesta debe tener dos partes separadas por '---'. La primera parte será un título corto para la actividad. La segunda parte será la lista de problemas o ejercicios con instrucciones claras.";

                    $response = Http::withHeaders([
                        'X-goog-api-key' => $apiKey,
                        'Content-Type' => 'application/json',
                    ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent', [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->failed()) {
                        Notification::make()
                            ->title('Error de API')
                            ->body('Hubo un error al contactar la API de Gemini. Código: ' . $response->status() . '. Respuesta: ' . $response->body())
                            ->danger()
                            ->send();
                        return;
                    }

                    $generatedText = $response->json('candidates.0.content.parts.0.text');

                    if (!$generatedText) {
                        Notification::make()
                            ->title('Error de Respuesta')
                            ->body('La API de Gemini no devolvió contenido.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $parts = explode('---', $generatedText, 2);
                    $taskName = trim($parts[0]);
                    $taskDescription = isset($parts[1]) ? trim($parts[1]) : '';

                    return redirect()->to(static::getResource()::getUrl('create', [
                        'name' => $taskName,
                        'description' => $taskDescription,
                    ]));
                }),
            Actions\CreateAction::make(),
        ];
    }
}
