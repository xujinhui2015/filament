<?php

namespace App\Services\Filament;

use Illuminate\Support\Facades\File;
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
        foreach ($extends as $extendConfig) {
            if (!$extendConfig['enabled']) {
                foreach ($extendConfig['namespaces'] as $namespace) {
                    // 资源文件过滤
                    $resources = glob(app_path('Filament' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $namespace) . '/*Resource.php');
                    $excludes = array_merge($excludes, array_map(function ($resource) {
                        return pathinfo($resource, PATHINFO_FILENAME);
                    }, $resources));
                    // 资源集群过滤
                    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(app_path('Filament' . DIRECTORY_SEPARATOR . 'Clusters' . DIRECTORY_SEPARATOR . $namespace)));
                    $clusters = new RegexIterator($iterator, '/^.+Resource\.php$/i', RegexIterator::GET_MATCH);
                    foreach ($clusters as $cluster) {
                        $excludes[] = pathinfo($cluster[0], PATHINFO_FILENAME);
                    }
                }
            }
        }
        return $excludes;
    }

}
