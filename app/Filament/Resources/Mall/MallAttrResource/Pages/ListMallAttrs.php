<?php

namespace App\Filament\Resources\Mall\MallAttrResource\Pages;

use App\Filament\Resources\Mall\MallAttrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMallAttrs extends ListRecords
{
    protected static string $resource = MallAttrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
