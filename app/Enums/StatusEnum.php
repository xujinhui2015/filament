<?php

namespace App\Enums;

use App\Traits\EnumTraits;

enum StatusEnum: int
{
    use EnumTraits;

    case Open = 1;
    case Clone = 0;
}
