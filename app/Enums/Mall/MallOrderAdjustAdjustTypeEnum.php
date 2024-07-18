<?php

namespace App\Enums\Mall;

use Filament\Support\Contracts\HasLabel;

enum MallOrderAdjustAdjustTypeEnum: string implements HasLabel
{
    case Postage = 'postage';


    public function getLabel(): string
    {
        return match ($this) {
            self::Postage => '运费',
        };
    }
}
