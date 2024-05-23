<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Filament\Resources\Mall\MallOrderResource\Pages;
use App\Models\Mall\MallOrder;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallOrderResource extends Resource
{
    protected static ?string $model = MallOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '订单管理';
    protected static ?string $modelLabel = '订单';
    protected static ?string $navigationGroup = '商城';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMallOrder::class
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('订单信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('order_no')->label('订单号'),
                                TextEntry::make('order_no')->label('订单来源'),
                            ]),
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.nickname')
                    ->label('会员名称'),
                Tables\Columns\ImageColumn::make('customer.avatar_url')
                    ->circular()
                    ->label('会员头像'),
                Tables\Columns\TextColumn::make('order_no')
                    ->searchable()
                    ->label('订单号'),
                Tables\Columns\TextColumn::make('order_status')
                    ->formatStateUsing(fn(int $state): string => MallOrderOrderStatusEnum::tryFrom($state)->text())
                    ->label('订单状态'),
                Tables\Columns\TextColumn::make('order_fact_money')
                    ->prefix('￥')
                    ->label('实付金额'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('收货人'),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('电话'),
                Tables\Columns\TextColumn::make('buyer_remark')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('买家备注'),
                Tables\Columns\TextColumn::make('seller_message')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('商家备注'),
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
                Tables\Filters\SelectFilter::make('order_status')
                    ->options(MallOrderOrderStatusEnum::options())
                    ->label('订单状态'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMallOrders::route('/'),
            'view' => Pages\ViewMallOrder::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return MallOrder::query()->count();
    }

}
