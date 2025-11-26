<?php

namespace App\Filament\Padre\Widgets;

use App\Models\TaskCompletion;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TareasCompletadasChart extends ChartWidget
{
    protected static ?string $heading = 'Tareas Completadas por Día (Últimos 7 Días)';
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

        $completions = TaskCompletion::whereIn('user_id', $hijosIds)
            ->where('completed_at', '>=', Carbon::now()->subDays(7))
            ->get()
            ->groupBy(function ($completion) {
                return Carbon::parse($completion->completed_at)->format('Y-m-d');
            });

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('D, M j'); // e.g., Mon, Nov 24
            $data[] = $completions->get($dateString, collect())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tareas Completadas',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
