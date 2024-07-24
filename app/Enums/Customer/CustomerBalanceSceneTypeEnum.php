<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum CustomerBalanceSceneTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Unknown = 0;
    case MallOrder = 1;

    public function getLabel(): string
    {
        return match ($this) {
            self::Unknown => '未知',
            self::MallOrder => '商城订单',
        };
    }
}
