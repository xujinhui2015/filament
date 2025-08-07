<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallGoodsCategoryResource\Pages;

use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallGoodsCategoryResource;
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
