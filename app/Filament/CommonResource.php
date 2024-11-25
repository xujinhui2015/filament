<?php

namespace App\Filament;

use Filament\Resources\Resource;

/**
 * 公共资源类
 */
abstract class CommonResource extends Resource
{
    /**
     * 全局搜索：结果限制个数
     */
    protected static int $globalSearchResultsLimit = 20;
    /**
     * 全局搜索：开关
     */
    protected static bool $isGloballySearchable = true;
}
