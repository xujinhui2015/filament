<?php

namespace App\Filament\Resources\Mall\MallOrderResource\Widgets;

use App\Filament\Resources\Mall\MallOrderResource\Pages\ListMallOrders;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MallOrderStatOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListMallOrders::class;
    }

    protected function getStats(): array
    {
        return [
//            Stat::make('Unique views', '192.1k')
//                ->description('32k increase')
//                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->chart([7, 2, 10, 3, 15, 4, 17])
//                ->color('success'),
            Stat::make('订单数', $this->getPageTableRecords()->total()),
            Stat::make('总金额', '￥' . money_cast_get($this->getPageTableQuery()->sum('order_fact_money'))),
            Stat::make('销售件数', $this->getPageTableQuery()->sum('detail.goods_number')),
        ];
    }
}
