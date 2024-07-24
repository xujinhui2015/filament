<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum MallOrderPaymentEnum: int implements HasLabel
{
    use EnumTrait;

    case Balance = 0;
    case Wechat = 1;


    public function getLabel(): string
    {
        return match ($this) {
            self::Balance => '余额支付',
            self::Wechat => '微信支付',
        };
    }
}
