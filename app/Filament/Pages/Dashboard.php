<?php
namespace App\Filament\Pages;

use Filament\Facades\Filament;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }
}
