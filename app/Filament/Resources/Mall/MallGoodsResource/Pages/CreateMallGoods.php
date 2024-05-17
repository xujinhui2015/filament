<?php

namespace App\Filament\Resources\Mall\MallGoodsResource\Pages;

use App\Filament\Resources\Mall\MallGoodsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMallGoods extends CreateRecord
{
    protected static string $resource = MallGoodsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? MallGoodsResource::getUrl();
    }
}
