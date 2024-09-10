<?php

namespace App\Filament\Resources\Mall\MallAttrResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttrValueRelationManager extends RelationManager
{
    protected static string $relationship = 'attrValue';

    protected static ?string $title =  '规格值';

    protected static ?string $modelLabel = '规格值';

    public function form(Form $form): Form
    {
        return $form
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
                Tables\Actions\CreateAction::make(),
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
}
