<?php

namespace App\Filament\Admin\Resources;

use App\Filament\CommonResource;

abstract class AdminResource extends CommonResource
{
    /**
     * 是否显示导航
     */
    public static function shouldRegisterNavigation(): bool
    {
        return config('extend.custom.admin.enabled');
    }
}
