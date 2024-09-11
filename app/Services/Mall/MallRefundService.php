<?php
namespace App\Services\Mall;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderRefund;
use App\Services\Customer\CustomerService;
use Illuminate\Support\Facades\DB;

class MallRefundService
{
    /**
     * 确认退款
     */
    public static function confirmedRefund(MallOrderRefund $orderRefund): void
    {
        DB::transaction(function () use ($orderRefund) {
            $orderRefund->update([
                'refund_status' => MallOrderRefundRefundStatusEnum::Confirmed
            ]);
            // 执行退款
            if ($orderRefund->order->payment->isEq(MallOrderPaymentEnum::Balance)) {
                // 余额退款
                CustomerService::setBalance(
                    $orderRefund->order->customer,
                    $orderRefund->refund_money,
                    CustomerBalanceSceneTypeEnum::MallOrderRefund,
                    $orderRefund->id
                );
                // 扣除库存
                MallStockService::handleOrderRefundStock($orderRefund);

                $orderRefund->update([
                    'refund_status' => MallOrderRefundRefundStatusEnum::Successful
                ]);
            } elseif ($orderRefund->order->payment->isEq(MallOrderPaymentEnum::Wechat)) {
                return ;
            }
        });
    }
}
