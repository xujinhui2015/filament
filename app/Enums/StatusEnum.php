<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;

enum StatusEnum: int
{
    use EnumTrait;

    case Open = 1;
    case Clone = 0;
}
