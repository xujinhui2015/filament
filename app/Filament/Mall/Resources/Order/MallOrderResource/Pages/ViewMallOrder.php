<?php

namespace App\Filament\Mall\Resources\Order\MallOrderResource\Pages;

use App\Filament\Mall\Resources\Order\MallOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMallOrder extends ViewRecord
{
    protected static string $resource = MallOrderResource::class;

    protected static ?string $title = '订单详情';

    protected function getHeaderActions(): array
    {
        return [
            MallOrderResource::getDeliveryFromOption(Actions\EditAction::make()),
        ];
    }

}
