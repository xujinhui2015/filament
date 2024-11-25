<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallRefundAddressResource\Pages;

use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallRefundAddressResource;
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
