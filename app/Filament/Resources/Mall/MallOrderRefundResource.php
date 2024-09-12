<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Filament\Resources\Mall\MallOrderRefundResource\Pages;
use App\Filament\Resources\Mall\MallOrderRefundResource\RelationManagers;
use App\Models\Mall\MallOrderRefund;
use App\Models\Mall\MallRefundAddress;
use App\Services\Customer\CustomerService;
use App\Services\Mall\MallRefundService;
use App\Services\Mall\MallStockService;
use App\Support\Exceptions\ResponseException;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Actions\MountableAction;
use Filament\Forms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class MallOrderRefundResource extends MallResource implements HasShieldPermissions
{
    protected static ?string $model = MallOrderRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '售后管理';
    protected static ?string $modelLabel = '售后';
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

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->refund_order_no ?? '-';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['refund_order_no', 'phone', 'refund_reason', 'buyer_message', 'seller_message'];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMallOrderRefund::class,
            Pages\OrderRefundOperationLog::class
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
                                TextEntry::make('order.customer.nickname')->label('名称'),
                                ImageEntry::make('order.customer.avatar_url')->circular()->height(30)->label('头像'),
                            ]),
                    ]),
                Fieldset::make('退款信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('refund_order_no')->label('退款单号'),
                                TextEntry::make('phone')->label('联系人电话'),
                                TextEntry::make('refund_type')->label('退款类型'),
                                TextEntry::make('refund_status')->label('退款状态'),
                            ]),
                        TextEntry::make('refund_reason')->columnSpanFull()->label('退款原因'),
                        TextEntry::make('buyer_message')->columnSpanFull()->label('买家留言'),
                        TextEntry::make('refund_money')->prefix('￥')->columnSpanFull()->size(TextEntry\TextEntrySize::Large)->color(Color::Red)->label('退款金额'),
                        ImageEntry::make('buyer_images')->columnSpanFull()->label('买家图片凭证'),
                        TextEntry::make('seller_message')->columnSpanFull()->default('无')->label('卖家留言'),

                    ]),
                Fieldset::make('买家退货物流信息')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('logistics.logistics_company_name')->label('物流公司名称'),
                                TextEntry::make('logistics.logistics_no')->label('快递单号'),
                            ]),
                        TextEntry::make('logistics.contactInfo')->label('退货联系信息'),
                    ])
                    ->hidden(
                        fn(MallOrderRefund $record) =>
                        $record->refund_type->isNeq(MallOrderRefundRefundTypeEnum::Return)
                        || $record->refund_status->in([
                            MallOrderRefundRefundStatusEnum::Applied,
                            MallOrderRefundRefundStatusEnum::Approved,
                        ])
                    )
            ]);
    }

    /**
     * @throws ResponseException
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.customer.nickname')
                    ->searchable()
                    ->label('会员名称'),
                Tables\Columns\ImageColumn::make('order.customer.avatar_url')
                    ->circular()
                    ->label('会员头像'),
                Tables\Columns\TextColumn::make('order.order_no')
                    ->searchable()
                    ->label('订单号'),
                Tables\Columns\TextColumn::make('refund_order_no')
                    ->searchable()
                    ->label('退款单号'),
                Tables\Columns\TextColumn::make('refund_type')
                    ->label('退款类型'),
                Tables\Columns\TextColumn::make('refund_status')
                    ->badge()
                    ->label('订单状态'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                ... self::getOrderOperationActions(Tables\Actions\Action::class),
            ])
            ->bulkActions([

            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMallOrderRefunds::route('/'),
            'view' => Pages\ViewMallOrderRefund::route('/{record}'),
            'log' => Pages\OrderRefundOperationLog::route('/{record}/log'),
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
        return MallOrderRefund::query()->count();
    }

    /**
     * 获取订单操作
     * @throws ResponseException
     */
    public static function getOrderOperationActions($agreedAction): array
    {
        return [
            // 仅退款
            $agreedAction::make('Agreed')
                ->requiresConfirmation()
                ->modalHeading('同意退款')
                ->modalSubmitActionLabel('同意')
                ->action(function(MallOrderRefund $record, array $arguments) {
                    $record->update([
                        'refund_status' => MallOrderRefundRefundStatusEnum::Approved
                    ]);
                    if ($arguments['refund'] ?? false) {
                        MallRefundService::confirmedRefund($record);
                    }
                })
                ->extraModalFooterActions(fn (MountableAction $action): array => [
                    $action->makeModalSubmitAction(name: '同意并确认退款', arguments: ['refund' => true]),
                ])
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        $record->refund_status->isNeq(MallOrderRefundRefundStatusEnum::Applied)
                        || $record->refund_type->isNeq(MallOrderRefundRefundTypeEnum::Only),
                )
                ->label('同意退款'),
            $agreedAction::make('Confirmed')
                ->requiresConfirmation()
                ->modalHeading('确认退款')
                ->action(function(MallOrderRefund $record) {
                    MallRefundService::confirmedRefund($record);
                })
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        !(($record->refund_type->isEq(MallOrderRefundRefundTypeEnum::Only)
                            && $record->refund_status->isEq(MallOrderRefundRefundStatusEnum::Approved))
                        ||
                        ($record->refund_type->isEq(MallOrderRefundRefundTypeEnum::Return)
                            && $record->refund_status->isEq(MallOrderRefundRefundStatusEnum::ReturnReceived)))
                )
                ->label('确认退款'),
            // 退货退款
            $agreedAction::make('AgreedRefund')
                ->requiresConfirmation()
                ->modalHeading('同意退货，并发送退货地址')
                ->form([
                    Forms\Components\Radio::make('refund_address_id')
                        ->required()
                        ->options(function () {
                            return MallRefundAddress::query()
                                ->get()
                                ->pluck('contact_info', 'id');
                        })->label('选择退货地址')
                ])
                ->action(function(MallOrderRefund $record, array $data) {
                    DB::transaction(function () use ($record, $data) {
                        $record->update([
                            'refund_status' => MallOrderRefundRefundStatusEnum::Approved->value
                        ]);
                        // 设置退货地址
                        $record->logistics()->updateOrCreate([], MallRefundAddress::query()->find($data['refund_address_id'])
                            ->only([
                                'name', 'phone', 'province', 'city', 'district', 'address',
                            ])
                        );
                    });
                    return $record;
                })
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        $record->refund_status->isNeq(MallOrderRefundRefundStatusEnum::Applied)
                        || $record->refund_type->isNeq(MallOrderRefundRefundTypeEnum::Return)
                )
                ->label('同意退货，发送退货地址'),
            $agreedAction::make('BuyerReturned')
                ->requiresConfirmation()
                ->modalHeading('确认收货，退款给买家')
                ->modalSubmitActionLabel('同意')
                ->action(function(MallOrderRefund $record) {
                    $record->update([
                        'refund_status' => MallOrderRefundRefundStatusEnum::ReturnReceived
                    ]);

                    if ($arguments['refund'] ?? false) {
                        MallRefundService::confirmedRefund($record);
                    }
                })
                ->extraModalFooterActions(fn (MountableAction $action): array => [
                    $action->makeModalSubmitAction(name: '同意并确认退款', arguments: ['refund' => true]),
                ])
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        $record->refund_status->isNeq(MallOrderRefundRefundStatusEnum::BuyerReturned)
                        || $record->refund_type->isNeq(MallOrderRefundRefundTypeEnum::Return),
                )
                ->label('买家已退货'),
        ];
    }
}
