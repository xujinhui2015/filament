<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Filament\Resources\Mall\MallOrderRefundResource\Pages;
use App\Filament\Resources\Mall\MallOrderRefundResource\RelationManagers;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderRefund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'name')
                    ->required(),
                Forms\Components\TextInput::make('refund_order_no')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('refund_type')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('refund_status')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('refund_money')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('refund_reason')
                    ->maxLength(500),
                Forms\Components\TextInput::make('buyer_message')
                    ->maxLength(500),
                Forms\Components\TextInput::make('buyer_images')
                    ->maxLength(1000),
                Forms\Components\TextInput::make('seller_message')
                    ->maxLength(500),
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
                    ->label('退款编号'),
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
//                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('agreed')
                    ->requiresConfirmation()
                    ->form([
                        // todo 做到这
                        Forms\Components\Placeholder::make('phone')
                            ->content(fn(MallOrderRefund $record) => $record->phone)
                            ->label('退货联系人电话'),
                    ])
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
                    ->extraModalFooterActions(fn (Tables\Actions\Action $action): array => [
                        $action->makeModalSubmitAction(name: '同意并确认退款', arguments: ['refund' => true]),
                    ])
                    ->hidden(
                        fn(MallOrderRefund $record) =>
                            MallOrderRefundRefundStatusEnum::Applied->isNeq($record->refund_status)
                            || MallOrderRefundRefundTypeEnum::Only->isNeq($record->refund_type),
                    )
                    ->label('同意退款'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMallOrderRefunds::route('/'),
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
}
