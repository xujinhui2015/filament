<?php

namespace App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallAttrResource\Pages;

use App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallAttrResource;
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
