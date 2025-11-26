<?php

namespace App\Filament\Padre\Widgets;

use App\Models\TaskCompletion;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class RendimientoPorCategoriaChart extends ChartWidget
{
    protected static ?string $heading = 'Rendimiento por Categoría';
    protected static ?string $pollingInterval = '15s';
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $padre = Auth::user();
        $hijosIds = $padre->children()->pluck('id');

        if ($hijosIds->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $completionsByCategory = TaskCompletion::whereIn('user_id', $hijosIds)
            ->join('tasks', 'task_completions.task_id', '=', 'tasks.id')
            ->select('tasks.category')
            ->get()
            ->groupBy('category')
            ->map(fn ($group) => $group->count());

        $labels = [
            'compresion_lectora' => 'Comprensión Lectora',
            'matematicas' => 'Matemáticas',
            'recreacion' => 'Juegos de Recreación',
        ];

        $data = [
            $completionsByCategory->get('compresion_lectora', 0),
            $completionsByCategory->get('matematicas', 0),
            $completionsByCategory->get('recreacion', 0),
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Tareas Completadas',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                    ],
                    'borderColor' => [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                ],
            ],
            'labels' => array_values($labels),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
