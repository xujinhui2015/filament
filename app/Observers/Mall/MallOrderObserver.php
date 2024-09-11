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
            $operationLog[] = [
                'user_id' => $userId,
                'action' => '更新订单状态',
                'operation' => '修改为 ' . $mallOrder->order_status->getLabel(),
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
