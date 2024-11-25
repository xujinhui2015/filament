<?php

namespace App\Filament\Mall\Resources\Order\MallOrderResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DetailRelationManager extends RelationManager
{
    protected static string $relationship = 'detail';

    protected static ?string $title =  '';

    protected static bool $isLazy = false; // 禁止延迟加载

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('goods.goods_sn')->label('商品编号'),
                Tables\Columns\TextColumn::make('goods_name')->label('商品名称'),
                Tables\Columns\TextColumn::make('goods_spec')->label('商品规格'),
                Tables\Columns\ImageColumn::make('goods_image')->square()->height(30)->label('商品图片'),
                Tables\Columns\TextColumn::make('goods_number')->label('数量'),
                Tables\Columns\TextColumn::make('goods_price')->prefix('￥')->label('实际付款金额'),
            ])
            ->paginated(false);
    }
}
