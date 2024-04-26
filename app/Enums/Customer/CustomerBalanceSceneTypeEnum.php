<?php

namespace App\Enums\Customer;

use App\Traits\EnumTraits;

enum CustomerBalanceSceneTypeEnum: int
{
    use EnumTraits;

    case Unknown = 0;

    public function text(): string
    {
        return match ($this) {
            self::Unknown => '未知',
        };
    }
}
