<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\OllamaRequestLog; // Import the model

class OllamaLearningModel extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static string $view = 'filament.pages.ollama-learning-model';

    protected static ?string $navigationLabel = 'Modelo Ollama';
    protected static ?string $title = 'Modelo Ollama';
    protected static ?string $slug = 'ollama-learning-model';

    protected static ?string $navigationGroup = 'Modelos de Aprendizajes';

    public $totalRequests;
    public $latestRequests;

    public function mount(): void
    {
        $this->totalRequests = OllamaRequestLog::count();
        $this->latestRequests = OllamaRequestLog::orderByDesc('created_at')
                                                ->where('status', 'success')
                                                ->take(3)
                                                ->get();
    }
}
