<?php

namespace App\Enums;

use App\Support\Traits\EnumTrait;

enum TokenEnum: string
{
    use EnumTrait;

    // Token 类型
    case Mini = 'mini'; // 小程序登录
}
