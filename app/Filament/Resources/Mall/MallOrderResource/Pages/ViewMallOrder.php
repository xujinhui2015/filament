<?php

namespace App\Filament\Resources\Mall\MallOrderResource\Pages;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Filament\Resources\Mall\MallOrderResource;
use App\Models\Mall\MallExpress;
use App\Models\Mall\MallOrder;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Carbon;

class ViewMallOrder extends ViewRecord
{
    protected static string $resource = MallOrderResource::class;

    protected static ?string $title = '订单详情';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->form([
                    Select::make('logistics_name')
                        ->options(MallExpress::query()->pluck('express_name', 'express_name'))
                        ->required()
                        ->label('物流公司'),
                    TextInput::make('logistics_no')
                        ->maxLength(128)
                        ->required()
                        ->label('物流单号'),
                ])
                ->using(function (MallOrder $record, array $data): MallOrder {

                    $record->update([
                        ... $data,
                        'order_status' => MallOrderOrderStatusEnum::Delivery->value,
                        'delivery_time' => Carbon::now(),
                    ]);

                    return $record;
                })
//                ->hidden(function (MallOrder $record) {
//                    dump($record);
//                    exit;
//                })
                ->hidden(fn(MallOrder $record) => MallOrderOrderStatusEnum::Pay->isNeq($record->order_status))
                ->label('发货'),
        ];
    }

}
