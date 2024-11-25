<?php
namespace App\Filament\Admin\Pages;

use Filament\Facades\Filament;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getWidgets(): array
    {
        return Filament::getWidgets();
    }
}
