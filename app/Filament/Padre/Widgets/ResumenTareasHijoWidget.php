<?php

namespace App\Filament\Padre\Widgets;

use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ResumenTareasHijoWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $padre = Auth::user();
        $hijosIds = $padre->children()->pluck('id');

        if ($hijosIds->isEmpty()) {
            return [
                Stat::make('Sin Hijos Asignados', 'No hay datos para mostrar')
                    ->description('Asigne hijos a su cuenta para ver el progreso.')
                    ->icon('heroicon-o-exclamation-circle')
                    ->color('warning'),
            ];
        }

        $categories = [
            'compresion_lectora' => 'Comprensión Lectora',
            'matematicas' => 'Matemáticas',
            'recreacion' => 'Juegos de Recreación',
        ];

        $stats = [];

        foreach ($categories as $categoryKey => $categoryName) {
            $totalTasks = Task::where('category', $categoryKey)->where('is_published', true)->count();
            
            if ($totalTasks === 0) {
                $percentage = 0;
            } else {
                $completedTasks = TaskCompletion::whereIn('user_id', $hijosIds)
                    ->whereHas('task', function ($query) use ($categoryKey) {
                        $query->where('category', $categoryKey);
                    })
                    ->distinct('task_id')
                    ->count();
                
                $percentage = round(($completedTasks / $totalTasks) * 100);
            }

            $stats[] = Stat::make($categoryName, "{$percentage}% Completado")
                ->description("{$completedTasks} de {$totalTasks} tareas")
                ->icon($this->getIconForCategory($categoryKey))
                ->color($this->getColorForCategory($categoryKey));
                
        }

        return $stats;
    }

    private function getIconForCategory(string $category): string
    {
        return match ($category) {
            'compresion_lectora' => 'heroicon-o-book-open',
            'matematicas' => 'heroicon-o-calculator',
            'recreacion' => 'heroicon-o-puzzle-piece',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    private function getColorForCategory(string $category): string
    {
        return match ($category) {
            'compresion_lectora' => 'info',
            'matematicas' => 'success',
            'recreacion' => 'warning',
            default => 'gray',
        };
    }
}
