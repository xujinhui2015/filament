<?php

namespace App\Filament\Mall\Resources\Order\MallOrderRefundResource\Pages;

use App\Filament\Mall\Resources\Order\MallOrderRefundResource;
use App\Support\Exceptions\ResponseException;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMallOrderRefund extends ViewRecord
{
    protected static string $resource = MallOrderRefundResource::class;

    protected static ?string $title = '售后详情';

    /**
     * @throws ResponseException
     */
    protected function getHeaderActions(): array
    {
        return MallOrderRefundResource::getOrderOperationActions(EditAction::class);
    }
}
