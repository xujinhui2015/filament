<?php

namespace App\Filament\Resources\Mall\MallGoodsResource\Pages;

use App\Filament\Resources\Mall\MallGoodsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMallGoods extends ManageRecords
{
    protected static string $resource = MallGoodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
