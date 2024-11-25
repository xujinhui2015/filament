<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallExpressResource\Pages;

use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallExpressResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMallExpresses extends ManageRecords
{
    protected static string $resource = MallExpressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
