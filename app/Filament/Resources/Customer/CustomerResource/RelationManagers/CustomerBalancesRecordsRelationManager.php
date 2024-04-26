<?php

namespace App\Filament\Resources\Customer\CustomerResource\RelationManagers;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Services\FilamentCommonService;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerBalancesRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'customerBalanceRecords';

    protected static ?string $title =  '余额变动';

    protected static ?string $modelLabel = '余额变动';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

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
                    ->options(CustomerBalanceSceneTypeEnum::options())
                    ->label('场景'),
                FilamentCommonService::getFilterDateRange('created_at'),
            ]);
    }
}
