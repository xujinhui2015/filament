<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum IsYesOrNoEnum: int implements HasLabel
{
    use EnumTrait;

    case Yes = 1;
    case No = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::Yes => '是',
            self::No => '否',
        };
    }
}
