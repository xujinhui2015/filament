<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;

enum CustomerPointsSceneTypeEnum: int
{
    use EnumTrait;

    case Unknown = 0;

    public function text(): string
    {
        return match ($this) {
            self::Unknown => '未知',
        };
    }
}
