<?php

namespace App\Filament\Resources\Mall;

use Filament\Resources\Resource;

abstract class MallResource extends Resource
{
    /**
     * 全局搜索：结果限制个数
     */
    protected static int $globalSearchResultsLimit = 20;
    /**
     * 全局搜索：开关
     */
    protected static bool $isGloballySearchable = true;

    public static function shouldRegisterNavigation(): bool
    {
        return config('extend.custom.mall.enabled');
    }

}
