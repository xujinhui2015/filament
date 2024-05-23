<?php

namespace App\Enums\Mall;

enum MallOrderAdjustAdjustTypeEnum: string
{
    case Postage = 'postage';


    public function text(): string
    {
        return match ($this) {
            self::Postage => '运费',
        };
    }
}
