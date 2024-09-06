<?php

namespace App\Enums\Mall;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum MallOrderRefundRefundTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Only = 0;
    case Return = 1;


    public function getLabel(): string
    {
        return match ($this) {
            self::Only => '仅退款',
            self::Return => '退货退款',
        };
    }
}
