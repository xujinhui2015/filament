<?php

namespace App\Filament\Resources\Mall\MallOrderRefundResource\Pages;

use App\Filament\Resources\Mall\MallOrderRefundResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMallOrderRefund extends ViewRecord
{
    protected static string $resource = MallOrderRefundResource::class;

    protected function getHeaderActions(): array
    {
        return MallOrderRefundResource::getOrderOperationActions(EditAction::class);
    }
}
