<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum StatusEnum: int implements HasLabel
{
    use EnumTrait;

    case Open = 1;
    case Clone = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => '开启',
            self::Clone => '关闭',
        };
    }
}
