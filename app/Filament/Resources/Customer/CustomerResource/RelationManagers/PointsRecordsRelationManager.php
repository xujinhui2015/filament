<?php

namespace App\Filament\Resources\Customer\CustomerResource\RelationManagers;

use App\Enums\Customer\CustomerPointsSceneTypeEnum;
use App\Services\FilamentService;
use Exception;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PointsRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'pointsRecords';

    protected static ?string $title =  '积分变动';

    protected static ?string $modelLabel = '积分变动';

    /**
     * @throws Exception
     */
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
                Tables\Filters\SelectFilter::make('change_explain')
                    ->options(CustomerPointsSceneTypeEnum::class)
                    ->label('场景'),
                FilamentService::getFilterDateRange('created_at'),
            ]);
    }
}
