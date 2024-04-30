<?php

namespace App\Enums;

enum HttpCodeEnum: int
{
    case TokenFail = 401;


    public function text(): string
    {
        return match ($this) {
            self::TokenFail => 'Token Error',
        };
    }
}
