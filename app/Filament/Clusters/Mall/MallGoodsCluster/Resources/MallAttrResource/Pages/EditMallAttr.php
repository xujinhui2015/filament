<?php

namespace App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallAttrResource\Pages;

use App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallAttrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMallAttr extends EditRecord
{
    protected static string $resource = MallAttrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
