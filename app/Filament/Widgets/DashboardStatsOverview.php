<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Task;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardStatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?int $columns = 3; // Now 3 columns for the 3 activity cards
    protected function getStats(): array
    {
        // Count active 'compresion_lectora' tasks
        $compresionLectoraCount = Task::where('category', 'compresion_lectora')
                                      ->where('is_published', true)
                                      ->count();

        // Count active 'matematicas' tasks
        $matematicasCount = Task::where('category', 'matematicas')
                                  ->where('is_published', true)
                                  ->count();

        // Count active 'recreacion' tasks
        $recreacionCount = Task::where('category', 'recreacion')
                               ->where('is_published', true)
                               ->count();

        return [
            Stat::make('Actividades Comprensión Lectora', $compresionLectoraCount)
                ->description('Actividades publicadas de Comprensión Lectora')
                ->color('success')
                ->chart([1, 3, 2, 5, 4, 6, 5]),
            Stat::make('Actividades Matemáticas', $matematicasCount)
                ->description('Actividades publicadas de Matemáticas')
                ->color('info')
                ->chart([1, 3, 2, 5, 4, 6, 5]),
            Stat::make('Actividades Recreación', $recreacionCount)
                ->description('Actividades publicadas de Recreación')
                ->color('warning')
                ->chart([1, 3, 2, 5, 4, 6, 5]),
        ];
    }
}
