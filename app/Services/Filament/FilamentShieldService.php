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
        $filterExtends = array_filter(config('extend.custom'), fn($config) => !$config['enabled']);

        // 所有模块都已开启
        if (!$filterExtends) {
            return [];
        }

        // 拿到开启的模块并且设置首字母为大写
        $excludes = implode(',', array_map(
            fn($panelName) => ucfirst($panelName),
            array_keys($filterExtends)
        ));

        // 渲染所有资源文件
        $resources = glob(
            app_path('Filament')
            . '/{' . $excludes . '}/{Resources,Clusters}{,/*,/*/*}/*Resource.php', GLOB_BRACE
        );

        return array_map(function ($resource) {
            return pathinfo($resource, PATHINFO_FILENAME);
        }, $resources);
    }

}
