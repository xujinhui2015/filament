<?php

namespace App\Filament\Mall\Resources\Goods\MallGoodsResource\Pages;

use App\Filament\Mall\Resources\Goods\MallGoodsResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMallGoods extends ListRecords
{
    protected static string $resource = MallGoodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
