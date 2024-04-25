<?php

namespace App\Filament\Resources\Customer\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerPointsRelationManager extends RelationManager
{
    protected static string $relationship = 'customerPoints';

    protected static ?string $title =  '积分变动';

    protected static ?string $modelLabel = '积分变动';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('points')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('时间'),
                Tables\Columns\TextColumn::make('change_explain')->label('说明'),
                Tables\Columns\TextColumn::make('points')->label('变动积分'),
                Tables\Columns\TextColumn::make('remark')->label('备注'),
            ])
            ->filters([

            ]);
    }
}
