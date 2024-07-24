<?php
namespace App\Services\Mall;

use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderDetail;

class MallStockService
{
    /**
     * 检查库存是否充足
     */
    public static function checkStock($goodsSkuId, $checkStock): bool
    {
        return MallGoodsSku::query()
                ->where('id', $goodsSkuId)
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

}
