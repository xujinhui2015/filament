<?php

namespace App\Services\Filament;

class FilamentShieldService
{
    public static function getExcludes(): array
    {
        $filterExtends = array_filter(config('extend.custom'), fn($config) => !$config['enabled']);
        if (empty($filterExtends)) {
            return [[], [], []];
        }

        $excludes = implode(',', array_map(
            fn($panelName) => ucfirst($panelName),
            array_keys($filterExtends)
        ));
        $globPath = app_path('Filament') . '/{' . $excludes . '}/';

        // 一次性获取所有文件，并按文件类型分类
        $allFiles = glob($globPath . '{Resources,Clusters,Widgets}{,/*,/*/*}/*.php', GLOB_BRACE);

        $excludeResources = [];
        $excludePages = [];
        $excludeWidgets = [];

        foreach ($allFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            if (str_ends_with($file, 'Resource.php')) {
                $excludeResources[] = $filename;
            } elseif (str_ends_with($file, 'Cluster.php')) {
                $excludePages[] = $filename;
            } elseif (str_contains($file, 'Widgets')) {
                $excludeWidgets[] = $filename;
            }
        }

        return [$excludeResources, $excludePages, $excludeWidgets];
    }

}
