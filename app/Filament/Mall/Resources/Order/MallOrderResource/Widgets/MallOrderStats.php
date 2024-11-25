<?php

namespace App\Filament\Mall\Resources\Order\MallOrderResource\Widgets;

use App\Filament\Mall\Resources\Order\MallOrderResource\Pages\ListMallOrders;
use App\Models\Mall\MallOrder;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MallOrderStats extends BaseWidget
{
    use InteractsWithPageTable;
    protected static ?string $pollingInterval = null;
    protected function getTablePage(): string
    {
        return ListMallOrders::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(MallOrder::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('订单数', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('总售价', format_large_number($this->getPageTableQuery()->sum('order_fact_money'))),
            Stat::make('平均单价', format_large_number($this->getPageTableQuery()->avg('order_fact_money'))),
        ];
    }
}
