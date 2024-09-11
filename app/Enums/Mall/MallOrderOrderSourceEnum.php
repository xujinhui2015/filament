<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum MallOrderOrderSourceEnum: int implements HasLabel
{
    use EnumTrait;

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
