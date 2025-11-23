<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TaskCompletion; // Import TaskCompletion model
use Carbon\Carbon;

class TaskCompletionsChart extends ChartWidget
{
    protected static ?string $heading = 'Finalizaciones de Tareas (Últimos 7 Días)';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Get task completions in the last 7 days
        $completions = TaskCompletion::where('completed_at', '>=', Carbon::now()->subDays(7))
                                     ->get()
                                     ->groupBy(function ($completion) {
                                         return Carbon::parse($completion->completed_at)->format('Y-m-d');
                                     });

        // Prepare data for the chart
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d'); // e.g., Nov 20
            $data[] = $completions->has($date->format('Y-m-d')) ? $completions[$date->format('Y-m-d')]->count() : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total de Finalizaciones',
                    'data' => $data,
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
                        'stepSize' => 1, // Ensure y-axis shows whole numbers for completion counts
                    ],
                ],
            ],
        ];
    }
}
