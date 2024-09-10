<?php

namespace App\Services\Filament;

class FilamentShieldService
{
    /**
     * 获取需要排除的资源
     */
    public static function getExcludeResources(): array
    {
        $extends = config('extend.custom');
        $excludes = [];
        foreach ($extends as $extendConfig) {
            if (!$extendConfig['enabled']) {
                foreach ($extendConfig['namespaces'] as $namespace) {
                    $resources = glob(app_path('Filament' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $namespace) . '/*Resource.php');
                    $excludes = array_merge($excludes, array_map(function ($resource) {
                        return pathinfo($resource, PATHINFO_FILENAME);
                    }, $resources));
                }
            }
        }
        return $excludes;
    }

}
