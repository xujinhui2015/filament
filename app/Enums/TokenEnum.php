<?php

namespace App\Enums;

use App\Traits\EnumTraits;

enum TokenEnum: string
{
    use EnumTraits;

    // Token 类型
    case Mini = 'mini'; // 小程序登录
}
