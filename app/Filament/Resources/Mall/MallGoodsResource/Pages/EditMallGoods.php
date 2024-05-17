<?php

namespace App\Filament\Resources\Mall\MallGoodsResource\Pages;

use App\Filament\Resources\Mall\MallGoodsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMallGoods extends EditRecord
{
    protected static string $resource = MallGoodsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? MallGoodsResource::getUrl();
    }
}
