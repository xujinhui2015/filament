<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Filament\Resources\Mall\MallOrderRefundResource\Pages;
use App\Filament\Resources\Mall\MallOrderRefundResource\RelationManagers;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderRefund;
use Filament\Actions\EditAction;
use Filament\Actions\MountableAction;
use Filament\Forms;
use Filament\Forms\Form;
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
use Hugomyb\FilamentMediaAction\Infolists\Components\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class MallOrderRefundResource extends Resource
{
    protected static ?string $model = MallOrderRefund::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = '售后管理';
    protected static ?string $modelLabel = '售后';
    protected static ?string $navigationGroup = '商城';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

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
                                TextEntry::make('refund_type')->formatStateUsing(fn(int $state): string => MallOrderRefundRefundTypeEnum::tryFrom($state)->getLabel())->label('退款类型'),
                                TextEntry::make('refund_status')->formatStateUsing(fn(int $state): string => MallOrderRefundRefundStatusEnum::tryFrom($state)->getLabel())->label('退款状态'),
                            ]),
                        TextEntry::make('refund_reason')->columnSpanFull()->label('退款原因'),
                        TextEntry::make('buyer_message')->columnSpanFull()->label('买家留言'),
                        TextEntry::make('refund_money')->prefix('￥')->columnSpanFull()->size(TextEntry\TextEntrySize::Large)->color(Color::Red)->label('退款金额'),
                        ImageEntry::make('buyer_images')->columnSpanFull()->label('买家图片凭证'),
                        TextEntry::make('seller_message')->columnSpanFull()->default('无')->label('卖家留言'),

                    ]),
            ]);
    }

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
                    ->formatStateUsing(fn(int $state): string => MallOrderRefundRefundTypeEnum::tryFrom($state)->getLabel())
                    ->label('退款类型'),
                Tables\Columns\TextColumn::make('refund_status')
                    ->formatStateUsing(fn(int $state): string => MallOrderRefundRefundStatusEnum::tryFrom($state)->getLabel())
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
//                Tables\Actions\EditAction::make(),

//                Tables\Actions\Action::make('agreed')
//                    ->requiresConfirmation()
//                    ->action(function(MallOrderRefund $record, array $arguments) {
//
//                        $record->update([
//                            'refund_status' => MallOrderRefundRefundStatusEnum::Approved
//                        ]);
//
//                        if ($arguments['refund'] ?? false) {
//                            $record->update([
//                                'refund_status' => MallOrderRefundRefundStatusEnum::Confirmed
//                            ]);
//                        }
//                    })
//                    ->extraModalFooterActions(fn (Tables\Actions\Action $action): array => [
//                        $action->makeModalSubmitAction(name: '同意并确认退款', arguments: ['refund' => true]),
//                    ])
//                    ->hidden(
//                        fn(MallOrderRefund $record) =>
//                            MallOrderRefundRefundStatusEnum::Applied->isNeq($record->refund_status)
//                            || MallOrderRefundRefundTypeEnum::Only->isNeq($record->refund_type),
//                    )
//                    ->label('同意退款'),
//                Tables\Actions\EditAction::make('agreed_refund')
//                    ->form([
//                        Forms\Components\TextInput::make('seller_message')->label('卖家留言')
//                    ])
//                    ->using(function (MallOrderRefund $record, array $data) {
//
//                        dump($data);
//                        exit;
//                    })
//                    ->hidden(
//                        fn(MallOrderRefund $record) =>
//                            MallOrderRefundRefundStatusEnum::Applied->isNeq($record->refund_status)
//                            || MallOrderRefundRefundTypeEnum::Return->isNeq($record->refund_type),
//                    )
//                    ->label('同意退款并发送地址'),
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
     */
    public static function getOrderOperationActions($agreedAction): array
    {
        return [
            // 仅退款操作
            $agreedAction::make('agreed')
                ->requiresConfirmation()
                ->action(function(MallOrderRefund $record, array $arguments) {
                    $record->update([
                        'refund_status' => MallOrderRefundRefundStatusEnum::Approved
                    ]);
                    if ($arguments['refund'] ?? false) {
                        $record->update([
                            'refund_status' => MallOrderRefundRefundStatusEnum::Confirmed
                        ]);
                    }
                })
                ->extraModalFooterActions(fn (MountableAction $action): array => [
                    $action->makeModalSubmitAction(name: '同意并确认退款', arguments: ['refund' => true]),
                ])
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        MallOrderRefundRefundStatusEnum::Applied->isNeq($record->refund_status)
                        || MallOrderRefundRefundTypeEnum::Only->isNeq($record->refund_type),
                )
                ->label('同意退款'),
            $agreedAction::make('agreed')
                ->requiresConfirmation()
                ->action(function(MallOrderRefund $record, array $arguments) {
                    $record->update([
                        'refund_status' => MallOrderRefundRefundStatusEnum::Confirmed
                    ]);
                    // 执行退款


                })
                ->hidden(
                    fn(MallOrderRefund $record) =>
                        MallOrderRefundRefundStatusEnum::Approved->isNeq($record->refund_status)
                        || MallOrderRefundRefundTypeEnum::Only->isNeq($record->refund_type),
                )
                ->label('确认退款'),
            // todo 退货退款操作

        ];
    }
}
