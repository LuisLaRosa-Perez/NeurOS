<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FastVlmModule extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-eye';
    protected static ?string $navigationLabel = 'Modelo Visual';

    protected static string $view = 'filament.pages.fast-vlm-module';
}
