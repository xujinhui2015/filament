<?php

namespace App\Filament\Resources\Mall\MallGoodsCategoryResource\Pages;

use App\Filament\Resources\Mall\MallGoodsCategoryResource;
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
