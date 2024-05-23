<?php

namespace App\Enums\Mall;

enum MallOrderOrderSourceEnum: int
{
    case Order = 0;
    case ShoppingCart = 1;


    public function text(): string
    {
        return match ($this) {
            self::Order => '直接下单',
            self::ShoppingCart => '购物车',
        };
    }
}
