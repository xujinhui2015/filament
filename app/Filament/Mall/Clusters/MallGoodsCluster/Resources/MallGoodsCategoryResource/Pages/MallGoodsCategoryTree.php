<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallGoodsCategoryResource\Pages;

use App\Filament\Mall\Clusters\MallGoodsCluster;
use App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallGoodsCategoryResource;
use SolutionForest\FilamentTree\Resources\Pages\TreePage;

class MallGoodsCategoryTree extends TreePage
{
    protected static string $resource = MallGoodsCategoryResource::class;

    protected static int $maxDepth = 2;

    protected static ?string $cluster = MallGoodsCluster::class;

    protected function getActions(): array
    {
        return [
            $this->getCreateAction(),
            // SAMPLE CODE, CAN DELETE
            //\Filament\Pages\Actions\Action::make('sampleAction'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected function hasDeleteAction(): bool
    {
        return false;
    }

    protected function hasEditAction(): bool
    {
        return true;
    }

    protected function hasViewAction(): bool
    {
        return false;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }

    // CUSTOMIZE ICON OF EACH RECORD, CAN DELETE
    // public function getTreeRecordIcon(?\Illuminate\Database\Eloquent\Model $record = null): ?string
    // {
    //     return null;
    // }
}
