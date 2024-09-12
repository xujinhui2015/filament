<?php

namespace App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallRefundAddressResource\Pages;

use App\Filament\Clusters\Mall\MallGoodsCluster\Resources\MallRefundAddressResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMallRefundAddresses extends ManageRecords
{
    protected static string $resource = MallRefundAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
