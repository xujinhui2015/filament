<?php

namespace App\Filament\Resources\Mall\MallOrderResource\Pages;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Filament\Resources\Mall\MallOrderResource;
use App\Models\Mall\MallOrder;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMallOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = MallOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    public function getTabs(): array
    {
        $list = MallOrderOrderStatusEnum::cases();
        $tabs = [];
        foreach ($list as $orderStatusEnum) {
            if (in_array($orderStatusEnum, [MallOrderOrderStatusEnum::Checkout, MallOrderOrderStatusEnum::Close])) {
                continue;
            }
            $tabs[$orderStatusEnum->name] =
                Tab::make($orderStatusEnum->text())
                    ->badge(MallOrder::query()->where('order_status', $orderStatusEnum)->count())
                    ->query(fn(Builder $query) => $query->where('order_status', $orderStatusEnum));
        }
        return [
            null => Tab::make('全部'),
            ... $tabs
        ];
    }
}
