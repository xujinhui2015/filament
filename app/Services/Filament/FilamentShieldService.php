<?php

namespace App\Services\Filament;

class FilamentShieldService
{
    /**
     * 获取需要排除的资源
     */
    public static function getExcludeResources() : array
    {
        $extends = config('extend.custom');
        $excludes = [];
        foreach ($extends as $extendConfig) {
            if (!$extendConfig['enabled']) {
                $excludes = array_merge($excludes, $extendConfig['resources']);
            }
        }
        return $excludes;
    }

}
