<?php

namespace App\Filament\Mall\Clusters;

use Filament\Clusters\Cluster;

class MallGoodsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = '业务配置';
    protected static ?string $title = '业务配置';
    protected static ?string $navigationGroup = '商品';

    public static function shouldRegisterNavigation(): bool
    {
        return config('extend.custom.mall.enabled');
    }

}
