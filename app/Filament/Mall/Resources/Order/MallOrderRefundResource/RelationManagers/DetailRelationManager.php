<?php

namespace App\Filament\Mall\Resources\Order\MallOrderRefundResource\RelationManagers;

use App\Services\Filament\FilamentService;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DetailRelationManager extends RelationManager
{
    protected static string $relationship = 'detail';

    protected static ?string $title = '退货商品';

    protected static bool $isLazy = false; // 禁止延迟加载

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('refund_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('refund_number')
            ->columns([
                Tables\Columns\TextColumn::make('orderDetail.goods.goods_sn')->label('商品编号'),
                Tables\Columns\TextColumn::make('orderDetail.goods_name')->label('商品名称'),
                Tables\Columns\TextColumn::make('orderDetail.goods_spec')->label('商品规格'),
                Tables\Columns\ImageColumn::make('orderDetail.goods_image')
                    ->action(FilamentService::actionShowMedia('orderDetail.goods_image'))
                    ->square()
                    ->height(30)
                    ->label('商品图片'),
                Tables\Columns\TextColumn::make('refund_number')->label('退货数量'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
