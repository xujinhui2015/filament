<?php

namespace App\Filament\Admin\Resources\Customer\CustomerResource\RelationManagers;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Services\Filament\FilamentService;
use Exception;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BalancesRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'balanceRecords';

    protected static ?string $title =  '余额变动';

    protected static ?string $modelLabel = '余额变动';

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('balance')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('时间'),
                Tables\Columns\TextColumn::make('change_explain')->label('说明'),
                Tables\Columns\TextColumn::make('balance')->label('变动余额'),
                Tables\Columns\TextColumn::make('remark')->label('备注'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('change_explain')
                    ->options(CustomerBalanceSceneTypeEnum::class)
                    ->label('场景'),
                FilamentService::getFilterDateRange('created_at'),
            ]);
    }
}
