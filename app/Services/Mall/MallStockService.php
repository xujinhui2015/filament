<?php
namespace App\Services\Mall;

use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderDetail;
use App\Models\Mall\MallOrderRefund;
use App\Models\Mall\MallOrderRefundDetail;

class MallStockService
{
    /**
     * 检查库存是否充足
     */
    public static function checkStock($goodsSkuId, $checkStock): bool
    {
        return MallGoodsSku::query()
                ->whereId($goodsSkuId)
                ->value('stock') >= $checkStock;
    }

    /**
     * 处理订单库存
     */
    public static function handleOrderStock(MallOrder $order): void
    {
        $order->detail->each(function (MallOrderDetail $detail) {
            $detail->goodsSku()->decrement('stock', $detail->goods_number);
        });
    }

    /**
     * 处理退款库存
     */
    public static function handleOrderRefundStock(MallOrderRefund $order): void
    {
        $order->detail->each(function (MallOrderRefundDetail $detail) {
            $detail->orderDetail->goodsSku()->increment('stock', $detail->refund_number);
        });
    }

}
