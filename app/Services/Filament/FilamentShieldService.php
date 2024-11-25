<?php

namespace App\Services\Filament;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class FilamentShieldService
{
    /**
     * 获取排除文件
     */
    public static function getExcludes(): array
    {
        $filterExtends = array_filter(config('extend.custom'), fn($config) => !$config['enabled']);

        // 所有模块都已开启
        if (!$filterExtends) {
            return [[], [], []];
        }

        // 拿到开启的模块并且设置首字母为大写
        $excludes = implode(',', array_map(
            fn($panelName) => ucfirst($panelName),
            array_keys($filterExtends)
        ));

        $globPath = app_path('Filament') . '/{' . $excludes . '}/';

        // 获取排除资源文件
        $excludeResources = array_map(
            function ($resource) {
                return pathinfo($resource, PATHINFO_FILENAME);
            },
            glob($globPath . '{Resources,Clusters}{,/*,/*/*}/*Resource.php', GLOB_BRACE)
        );


        // 获取排除页面文件
        $excludePages = array_map(
            function ($resource) {
                return pathinfo($resource, PATHINFO_FILENAME);
            },
            glob($globPath . '{Clusters}{,/*,/*/*}/*Cluster.php', GLOB_BRACE
            ));

        // 获取排除小组件文件
        $excludeWidgets = array_map(
            function ($resource) {
                return pathinfo($resource, PATHINFO_FILENAME);
            }, glob(
                $globPath . '{' . $excludes . '}/{Widgets}{,/*,/*/*}/*Cluster.php', GLOB_BRACE
            )
        );

        return [$excludeResources, $excludePages, $excludeWidgets];
    }

}
