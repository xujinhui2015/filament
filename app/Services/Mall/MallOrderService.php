<?php
namespace App\Services\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
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

    }
}
