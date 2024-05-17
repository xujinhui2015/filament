<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;

enum IsYesOrNoEnum: int
{
    use EnumTrait;

    case Yes = 1;
    case No = 0;

    public function text(): string
    {
        return match ($this) {
            self::Yes => '是',
            self::No => '否',
        };
    }
}
