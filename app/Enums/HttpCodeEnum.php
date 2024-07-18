<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum HttpCodeEnum: int implements HasLabel
{
    case TokenFail = 401;


    public function getLabel(): string
    {
        return match ($this) {
            self::TokenFail => 'Token Error',
        };
    }
}
