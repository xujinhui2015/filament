<?php

namespace App\Observers\Mall;

use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Models\Mall\MallOrderRefund;

class MallOrderRefundObserver
{
    public function created(MallOrderRefund $mallOrderRefund): void
    {
    }

    public function updated(MallOrderRefund $mallOrderRefund): void
    {
        $operationLog = [];

        $userId = auth()->id() ?? null;

        if ($mallOrderRefund->isDirty('refund_status')) {
            if (is_numeric($mallOrderRefund->refund_status)) {
                $orderStatus = MallOrderRefundRefundStatusEnum::tryFrom((int)$mallOrderRefund->refund_status);
            } else {
                $orderStatus = $mallOrderRefund->refund_status;
            }
            $operationLog[] = [
                'user_id' => $userId,
                'action' => '更新订单状态',
                'operation' => '修改为 ' . $orderStatus->getLabel(),
            ];
        }

        if ($mallOrderRefund->isDirty('seller_message')) {
            $operationLog[] = [
                'user_id' => $userId,
                'action' => '更新商家留言',
                'operation' => '留言内容：' . $mallOrderRefund->seller_message,
            ];
        }

        if ($operationLog) {
            $mallOrderRefund->operationLog()->createMany($operationLog);
        }
    }

    public function deleted(MallOrderRefund $mallOrderRefund): void
    {
    }

    public function restored(MallOrderRefund $mallOrderRefund): void
    {
    }

    public function forceDeleted(MallOrderRefund $mallOrderRefund): void
    {
    }
}
