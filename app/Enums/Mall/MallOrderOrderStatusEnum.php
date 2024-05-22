<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;

enum MallOrderOrderStatusEnum: int
{
    use EnumTrait;

    case ORDER = 0;
    case PAY = 1;
    case DELIVERY = 2;
    case REFUND = 3;
    case FINISH = 4;
    case CLOSE = 5;
    case CHECKOUT = 6;
    case CANCEL = 7;


    public function text(): string
    {
        return match ($this) {
            self::ORDER => '待付款',
            self::PAY => '待发货',
            self::DELIVERY => '待收货',
            self::REFUND => '退款处理',
            self::FINISH => '已完成',
            self::CLOSE => '已关闭',
            self::CHECKOUT => '锁单状态',
            self::CANCEL => '已取消',
        };
    }
}
