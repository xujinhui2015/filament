<?php

namespace App\Filament\Resources\Mall\MallOrderResource\Pages;

use App\Filament\Resources\Mall\MallOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMallOrder extends ViewRecord
{
    protected static string $resource = MallOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
