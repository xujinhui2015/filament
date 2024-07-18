<?php

namespace App\Filament\Resources\Mall;

use Filament\Resources\Resource;

abstract class MallResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return config('extend.custom.mall.enabled');
    }
}
