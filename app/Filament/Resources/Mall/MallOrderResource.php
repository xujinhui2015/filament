<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Filament\Exports\Mall\MallOrderExporter;
use App\Filament\Resources\Mall\MallOrderResource\Pages;
use App\Filament\Resources\Mall\MallOrderResource\RelationManagers\DetailRelationManager;
use App\Models\Mall\MallOrder;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Exception;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MallOrderResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = MallOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '订单管理';
    protected static ?string $modelLabel = '订单';
    protected static ?string $navigationGroup = '商城';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'update',
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMallOrder::class,
            Pages\OrderOperationLog::class
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('会员信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('customer.nickname')->label('名称'),
                                ImageEntry::make('customer.avatar_url')->circular() ->height(30)->label('头像'),
                            ]),
                    ]),
                Fieldset::make('订单信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('order_no')->label('订单号'),
                                TextEntry::make('order_status')->formatStateUsing(fn(int $state): string => MallOrderOrderStatusEnum::tryFrom($state)->text())->label('订单状态'),
                                TextEntry::make('payment')->formatStateUsing(fn(int $state): string => MallOrderPaymentEnum::tryFrom($state)->text())->label('支付方式'),
                                TextEntry::make('order_source')->formatStateUsing(fn(int $state): string => MallOrderOrderSourceEnum::tryFrom($state)->text())->label('订单来源'),
                            ]),
                        TextEntry::make('buyer_remark')->label('买家留言'),
                        TextEntry::make('seller_message')->label('卖家留言'),
                    ]),
                Fieldset::make('收货信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('name')->label('收货人姓名'),
                                TextEntry::make('phone')->label('收货人电话'),
                                TextEntry::make('logistics_name')->label('物流公司'),
                                TextEntry::make('logistics_no')->label('物流单号'),
                            ]),
                        TextEntry::make('full_address')->columnSpanFull()->label('详细地址'),

                    ]),
                Fieldset::make('金额明细')
                    ->schema([
                        TextEntry::make('order_money')->prefix('￥')->inlineLabel()->columnSpanFull()->label('商品总额'),
                        TextEntry::make('postage')->prefix('+ ￥')->inlineLabel()->columnSpanFull()->label('运费'),
                        TextEntry::make('order_fact_money')->prefix('￥')
                            ->inlineLabel()
                            ->columnSpanFull()
                            ->size(TextEntry\TextEntrySize::Large)
                            ->color(Color::Red)
                            ->label('实收金额'),
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('买家留言'),
                Tables\Columns\TextInputColumn::make('seller_message')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->width('10%')
                    ->label('商家留言'),
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
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(MallOrderExporter::class)
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            DetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMallOrders::route('/'),
            'view' => Pages\ViewMallOrder::route('/{record}'),
            'log' => Pages\OrderOperationLog::route('/{record}/log'),
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
