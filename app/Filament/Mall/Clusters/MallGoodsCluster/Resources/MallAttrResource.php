<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources;

use App\Filament\Mall\Clusters\MallGoodsCluster;
use App\Filament\Mall\Resources\MallResource;
use App\Models\Mall\MallAttr;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallAttrResource extends MallResource implements HasShieldPermissions
{
    protected static ?string $model = MallAttr::class;
    protected static ?string $cluster = MallGoodsCluster::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '商品规格';
    protected static ?string $modelLabel = '商品规格';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'restore',
            'reorder',
            'restore_any',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->attr_name ?? '-';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['attr_name'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('attr_name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->label('规格名称'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attr_name')
                    ->searchable()
                    ->label('规格名称'),
                Tables\Columns\TextColumn::make('attrValue.attr_value_name')
                    ->label('属性值'),
                Tables\Columns\ToggleColumn::make('is_disabled')
                    ->label('是否禁用'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('删除时间'),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('更新时间'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort')
            ->reorderable('sort');
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\RelationManagers\AttrValueRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\Pages\ListMallAttrs::route('/'),
            'create' => \App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\Pages\CreateMallAttr::route('/create'),
            'edit' => \App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\Pages\EditMallAttr::route('/{record}/edit'),
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
