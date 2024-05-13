<?php

namespace App\Filament\Resources\Mall;

use App\Filament\Resources\Mall\MallGoodsResource\Pages;
use App\Filament\Resources\Mall\MallGoodsResource\RelationManagers;
use App\Models\Mall\MallGoods;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallGoodsResource extends Resource
{
    protected static ?string $model = MallGoods::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '商品管理';
    protected static ?string $modelLabel = '商品管理';
    protected static ?string $navigationGroup = '商城';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('goods_sn')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('goods_category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('goods_name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('subtitle')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('main_img')
                    ->required()
                    ->maxLength(1000),
                Forms\Components\TextInput::make('images')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_sale')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('goods_sn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('goods_category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('goods_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subtitle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('main_img')
                    ->searchable(),
                Tables\Columns\TextColumn::make('images')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManageMallGoods::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
