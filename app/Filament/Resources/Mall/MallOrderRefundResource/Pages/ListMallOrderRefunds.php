<?php

namespace App\Filament\Resources\Mall\MallOrderRefundResource\Pages;

use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Filament\Resources\Mall\MallOrderRefundResource;
use App\Models\Mall\MallOrderRefund;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMallOrderRefunds extends ListRecords
{
    protected static string $resource = MallOrderRefundResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function getTabs(): array
    {
        $list = MallOrderRefundRefundStatusEnum::cases();
        $tabs = [];
        foreach ($list as $orderStatusEnum) {
            $tabs[$orderStatusEnum->name] =
                Tab::make($orderStatusEnum->getLabel())
                    ->badge(MallOrderRefund::query()->where('refund_status', $orderStatusEnum)->count())
                    ->query(fn(Builder $query) => $query->where('refund_status', $orderStatusEnum));
        }
        return [
            null => Tab::make('全部'),
            ... $tabs
        ];
    }
}
