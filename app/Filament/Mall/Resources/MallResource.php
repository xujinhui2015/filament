<?php

namespace App\Filament\Mall\Resources;

use App\Filament\CommonResource;

abstract class MallResource extends CommonResource
{

    public static function shouldRegisterNavigation(): bool
    {
        return config('extend.custom.mall.enabled');
    }

}
