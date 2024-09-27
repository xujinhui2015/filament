<?php
namespace App\Services\Mall;

use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Models\Mall\MallCart;
use App\Models\Mall\MallOrder;

class MallOrderService
{
    /**
     * 完成支付
     */
    public static function completedPay(MallOrder $order): void
    {
        $order->update([
            'pay_time' => now(),
            'order_status' => MallOrderOrderStatusEnum::Pay,
        ]);

        // 购物车下单，清空购物车
        if ($order->order_source->isEq(MallOrderOrderSourceEnum::ShoppingCart)) {
            MallCart::query()
                ->where('customer_id', $order->customer_id)
                ->whereIn('goods_sku_id', $order->detail->pluck('goods_sku_id'))
                ->get()
                ->each
                ->delete();
        }

    }
}
