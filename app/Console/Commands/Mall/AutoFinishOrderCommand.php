<?php

namespace App\Console\Commands\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Models\Mall\MallOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AutoFinishOrderCommand extends Command
{
    protected $signature = 'mall:auto-finish-order-command';

    protected $description = '自动确认完成订单';

    public function handle(): void
    {
        // 订单15天后自动确认收货
        MallOrder::query()
            ->where('order_status', MallOrderOrderStatusEnum::Delivery)
            ->where('delivery_time', '<', Carbon::now()->subDays(15))
            ->get()
            ->each
            ->update([
                'order_status' => MallOrderOrderStatusEnum::Finish,
                'finish_time' => Carbon::now(),
            ]);
    }
}
