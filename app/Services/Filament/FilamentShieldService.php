<?php

namespace App\Services\Filament;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class FilamentShieldService
{
    /**
     * 获取需要排除的资源
     */
    public static function getExcludeResources(): array
    {
        $extends = config('extend.custom');
        $excludes = [];
        foreach ($extends as $panelName => $extendConfig) {
            if ($extendConfig['enabled']) {
                continue;
            }
            $extendPath = 'Filament' . DIRECTORY_SEPARATOR . ucfirst($panelName);

            // 资源文件过滤
            $resources = glob(app_path($extendPath) . '/Resources/{,*/}/*Resource.php', GLOB_BRACE);
            // 资源集群过滤
            $clusters = glob(app_path($extendPath) . '/Clusters/MallGoodsCluster/Resources/*Resource.php');

            $excludes = array_merge($excludes, array_map(function ($resource) {
                return pathinfo($resource, PATHINFO_FILENAME);
            }, [
                ... $resources,
                ... $clusters,
            ]));
        }
        return $excludes;
    }

}
