<?php

namespace App\Filament\Resources\Mall;

use App\Enums\MenuTypeEnum;
use App\Enums\StatusEnum;
use App\Filament\Resources\Mall\MallGoodsCategoryResource\Pages;
use App\Filament\Resources\Mall\MallGoodsCategoryResource\RelationManagers;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsCategory;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallGoodsCategoryResource extends Resource
{
    protected static ?string $model = MallGoodsCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = '商品分类';
    protected static ?string $modelLabel = '商品分类';
    protected static ?string $navigationGroup = '商城';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('上级分类')
                    ->options(self::getParent())
                    ->default(0)
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('category_name')
                    ->required()
                    ->maxLength(100)
                    ->label('商品分类名称'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_name')
                    ->searchable()
                    ->label('商品分类名称'),
                Tables\Columns\ToggleColumn::make('is_disabled')
                    ->label('是否禁用'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMallGoodsCategories::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function getParent($isTop = true)
    {
        $parentMenu = MallGoodsCategory::query()
            ->where('parent_id', 0)
            ->where('is_disabled', false)
            ->pluck('category_name', 'id');
        if ($isTop) {
            $parentMenu->offsetSet(0, '一级菜单');
        }
        return $parentMenu->sortKeys();
    }
}
