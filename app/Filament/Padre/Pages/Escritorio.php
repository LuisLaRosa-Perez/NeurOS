<?php

namespace App\Filament\Padre\Pages;

use App\Filament\Padre\Widgets\RendimientoPorCategoriaChart;
use App\Filament\Padre\Widgets\ResumenTareasHijoWidget;
use App\Filament\Padre\Widgets\TareasCompletadasChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Escritorio extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Escritorio';

    protected static ?string $title = 'Escritorio';

    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [
            ResumenTareasHijoWidget::class,
            TareasCompletadasChart::class,
            RendimientoPorCategoriaChart::class,
        ];
    }
}
