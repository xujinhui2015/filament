<?php

namespace App\Filament\Widgets;

use App\Models\Customer\Customer;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class CustomersChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '用户数';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $trend = Trend::model(Customer::class)
            ->between(
                start: now()->subYear(),
                end: now()->endOfMonth(),
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => '用户数',
                    'data' => $trend->pluck('aggregate')->toArray(),
                    'fill' => 'start',
                ],
            ],
            'labels' => $trend->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
