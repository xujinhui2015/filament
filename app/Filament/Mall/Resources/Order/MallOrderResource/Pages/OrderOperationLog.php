<?php

namespace App\Filament\Mall\Resources\Order\MallOrderResource\Pages;

use App\Filament\Mall\Resources\Order\MallOrderResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class OrderOperationLog extends ManageRelatedRecords
{
    protected static string $resource = MallOrderResource::class;

    protected static string $relationship = 'operationLog';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title =  '订单状态变更日志';

    public static function getNavigationLabel(): string
    {
        return self::$title;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                Tables\Columns\TextColumn::make('action')->label('动作'),
                Tables\Columns\TextColumn::make('operation')->label('操作说明'),
                Tables\Columns\TextColumn::make('user.name')->label('操作人'),
                Tables\Columns\TextColumn::make('created_at')->label('操作时间'),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('没有订单状态变更日志')
            ->emptyStateDescription('');
    }
}
