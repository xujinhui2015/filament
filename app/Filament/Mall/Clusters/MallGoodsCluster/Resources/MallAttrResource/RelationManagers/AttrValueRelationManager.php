<?php

namespace App\Filament\Mall\Clusters\MallGoodsCluster\Resources\MallAttrResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AttrValueRelationManager extends RelationManager
{
    protected static string $relationship = 'attrValue';

    protected static ?string $title =  '规格值';

    protected static ?string $modelLabel = '规格值';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('attr_value_name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100)
                    ->label('规格值名称'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('mall_attr_value')
            ->columns([
                Tables\Columns\TextColumn::make('attr_value_name')->label('规格值名称'),
                Tables\Columns\ToggleColumn::make('is_disabled')->label('是否禁用'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
                    Actions\ForceDeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort')
            ->reorderable('sort');
    }
}
