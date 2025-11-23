<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Task;
use Carbon\Carbon;

class TasksChart extends ChartWidget
{
    protected static ?string $heading = 'Tareas Creadas (Últimos 7 Días)';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Get tasks created in the last 7 days
        $tasks = Task::where('created_at', '>=', Carbon::now()->subDays(7))
                     ->get()
                     ->groupBy(function ($task) {
                         return Carbon::parse($task->created_at)->format('Y-m-d');
                     });

        // Prepare data for the chart
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d'); // e.g., Nov 20
            $data[] = $tasks->has($date->format('Y-m-d')) ? $tasks[$date->format('Y-m-d')]->count() : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Tareas',
                    'data' => $data,
                    'borderColor' => '#36A2EB', // Blue color
                    'backgroundColor' => '#9BD0F5', // Lighter blue for fill
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1, // Ensure y-axis shows whole numbers for task counts
                    ],
                ],
            ],
        ];
    }
}
