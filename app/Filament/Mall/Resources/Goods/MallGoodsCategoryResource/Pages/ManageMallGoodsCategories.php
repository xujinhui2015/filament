<?php

namespace App\Filament\Mall\Resources\Goods\MallGoodsCategoryResource\Pages;

use App\Filament\Mall\Resources\Goods\MallGoodsCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMallGoodsCategories extends ManageRecords
{
    protected static string $resource = MallGoodsCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
