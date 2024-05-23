<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;

enum MallOrderOrderStatusEnum: int
{
    use EnumTrait;

    case Order = 0;
    case Pay = 1;
    case Delivery = 2;
    case Refund = 3;
    case Finish = 4;
    case Close = 5;
    case Checkout = 6;
    case Cancel = 7;


    public function text(): string
    {
        return match ($this) {
            self::Order => '待付款',
            self::Pay => '待发货',
            self::Delivery => '待收货',
            self::Refund => '退款处理',
            self::Finish => '已完成',
            self::Close => '已关闭',
            self::Checkout => '锁单状态',
            self::Cancel => '已取消',
        };
    }
}
