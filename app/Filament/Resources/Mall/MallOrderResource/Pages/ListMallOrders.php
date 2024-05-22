<?php

namespace App\Filament\Resources\Mall\MallOrderResource\Pages;

use App\Filament\Resources\Mall\MallOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMallOrders extends ListRecords
{
    protected static string $resource = MallOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
