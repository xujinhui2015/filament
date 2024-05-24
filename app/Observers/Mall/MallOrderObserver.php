<?php

namespace App\Observers\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Models\Mall\MallOrder;

class MallOrderObserver
{
    public function created(MallOrder $mallOrder): void
    {

    }

    public function updated(MallOrder $mallOrder): void
    {
        $operationLog = [];

        $userId = auth()->id() ?? null;

        // 调整订单状态
        if ($mallOrder->isDirty('order_status')) {
            if (is_numeric($mallOrder->order_status)) {
                $orderStatus = MallOrderOrderStatusEnum::tryFrom((int)$mallOrder->order_status);
            } else {
                /** @var MallOrderOrderStatusEnum $orderStatus */
                $orderStatus = $mallOrder->order_status;
            }

            $operationLog[] = [
                'user_id' => $userId,
                'action' => '更新订单状态',
                'operation' => '修改为 ' . $orderStatus->text(),
            ];
        }

        // 调整商家留言
        if ($mallOrder->isDirty('seller_message')) {
            $operationLog[] = [
                'user_id' => $userId,
                'action' => '更新商家留言',
                'operation' => '留言内容：' . $mallOrder->seller_message,
            ];
        }

        if ($operationLog) {
            $mallOrder->operationLog()->createMany($operationLog);
        }

    }

    public function deleted(MallOrder $mallOrder): void
    {

    }

    public function restored(MallOrder $mallOrder): void
    {

    }

    public function forceDeleted(MallOrder $mallOrder): void
    {

    }
}
