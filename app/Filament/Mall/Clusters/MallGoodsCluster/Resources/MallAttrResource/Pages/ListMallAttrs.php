<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\Pages;

use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource;
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
