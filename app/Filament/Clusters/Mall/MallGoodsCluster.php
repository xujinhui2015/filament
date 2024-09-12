<?php

namespace App\Filament\Clusters\Mall;

use Filament\Clusters\Cluster;

class MallGoodsCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    /**
     * 面包屑标题
     */
    protected static ?string $clusterBreadcrumb = '商品资料';

    protected static ?string $navigationLabel = '商品资料';

    protected static ?string $navigationGroup = '商城';

}
