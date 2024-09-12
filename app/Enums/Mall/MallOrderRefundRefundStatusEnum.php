<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MallOrderRefundRefundStatusEnum: int implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    //退款类型：0申请退款1同意退款2买家退货3卖家确认收货4确认退款5退款成功6退款失败7退款关闭
    //仅退款：0申请退款 -> 1同意退款 -> 4确认退款 -> 5退款成功
    //退货退款：0申请退款 -> 1同意退款 -> 2买家退货 -> 3卖家确认收货 -> 4确认退款 -> 5退款成功
    case Applied = 0;
    case Approved = 1;
    case BuyerReturned = 2;
    case ReturnReceived = 3;
    case Confirmed = 4;
    case Successful = 5;
    case Failed = 6;
    case Closed = 7;


    public function getLabel(): string
    {
        return match ($this) {
            self::Applied => '申请退款',
            self::Approved => '同意退款',
            self::BuyerReturned => '买家退货',
            self::ReturnReceived => '卖家确认收货',
            self::Confirmed => '确认退款',
            self::Successful => '退款成功',
            self::Failed => '退款失败',
            self::Closed => '退款关闭',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Applied, self::ReturnReceived, self::BuyerReturned, self::Confirmed => 'warning',
            self::Approved => 'info',
            self::Successful => 'success',
            self::Failed, self::Closed => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Applied => 'heroicon-o-hand-raised',
            self::Approved => 'heroicon-s-information-circle',
            self::BuyerReturned => 'heroicon-m-truck',
            self::ReturnReceived, self::Confirmed => 'heroicon-m-check-badge',
            self::Successful => 'heroicon-m-check-circle',
            self::Failed, self::Closed => 'heroicon-m-x-circle',
        };
    }
}
