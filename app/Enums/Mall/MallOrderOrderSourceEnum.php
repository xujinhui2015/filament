<?php

namespace App\Enums\Mall;

use Filament\Support\Contracts\HasLabel;

enum MallOrderOrderSourceEnum: int implements HasLabel
{
    case Order = 0;
    case ShoppingCart = 1;


    public function getLabel(): string
    {
        return match ($this) {
            self::Order => '直接下单',
            self::ShoppingCart => '购物车',
        };
    }
}
