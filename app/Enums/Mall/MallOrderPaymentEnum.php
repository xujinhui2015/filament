<?php

namespace App\Enums\Mall;

enum MallOrderPaymentEnum: int
{
    case Balance = 0;
    case Wechat = 1;


    public function text(): string
    {
        return match ($this) {
            self::Balance => '余额支付',
            self::Wechat => '微信支付',
        };
    }
}
