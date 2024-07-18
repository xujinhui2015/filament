<?php

namespace App\Enums\Customer;

use App\Support\Traits\EnumTrait;
use Filament\Support\Contracts\HasLabel;

enum CustomerBalanceSceneTypeEnum: int implements HasLabel
{
    use EnumTrait;

    case Unknown = 0;

    public function getLabel(): string
    {
        return match ($this) {
            self::Unknown => '未知',
        };
    }
}
