<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;

enum StatusEnum: int
{
    use EnumTrait;

    case Open = 1;
    case Clone = 0;

    public function text(): string
    {
        return match ($this) {
            self::Open => '开启',
            self::Clone => '关闭',
        };
    }
}
