<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MallOrderOrderStatusEnum: int implements HasColor, HasIcon, HasLabel
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


    public function getLabel(): string
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Order => 'info',
            self::Pay, self::Refund => 'warning',
            self::Delivery, self::Finish => 'success',
            self::Close, self::Checkout, self::Cancel => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Order => 'heroicon-s-currency-dollar',
            self::Pay, self::Delivery => 'heroicon-m-truck',
            self::Refund => 'heroicon-m-arrow-path',
            self::Finish => 'heroicon-m-check-circle',
            self::Close, self::Checkout => '',
            self::Cancel => 'heroicon-m-x-circle',
        };
    }


}
