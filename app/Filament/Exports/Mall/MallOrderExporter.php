<?php

namespace App\Filament\Exports\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Models\Mall\MallOrder;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class MallOrderExporter extends Exporter
{
    protected static ?string $model = MallOrder::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_no')->label('订单号'),
            ExportColumn::make('order_status')->formatStateUsing(fn(int $state): string => MallOrderOrderStatusEnum::tryFrom($state)->text())->label('订单状态'),
            ExportColumn::make('order_money')->label('订单金额'),
            ExportColumn::make('order_fact_money')->label('订单实付金额'),
            ExportColumn::make('name')->label('收货人姓名'),
            ExportColumn::make('phone')->label('收货人电话'),
            ExportColumn::make('full_address')->label('详细地址'),
            ExportColumn::make('detail_count')->counts('detail')->label('商品数量'),
            ExportColumn::make('created_at')->label('下单时间'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = '您的商城订单已导出完成,成功导出了 ' . number_format($export->successful_rows) . ' 行,';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= number_format($failedRowsCount) . ' 行导出失败.';
        }

        return $body;
    }
}
