<?php

namespace App\Filament\Resources\Mall;

use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Filament\Resources\Mall\MallOrderResource\Pages;
use App\Filament\Resources\Mall\MallOrderResource\Widgets\MallOrderStatOverview;
use App\Models\Mall\MallOrder;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_no')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('order_status')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_money')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_fact_money')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('order_source')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('user_coupon_id')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('province')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('district')
                    ->required()
                    ->maxLength(32),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('last_pay_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('pay_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('delivery_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('finish_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('cancel_time')
                    ->required(),
                Forms\Components\DateTimePicker::make('turnoff_time')
                    ->required(),
                Forms\Components\TextInput::make('logistics_name')
                    ->required()
                    ->maxLength(64),
                Forms\Components\TextInput::make('logistics_no')
                    ->required()
                    ->maxLength(128),
                Forms\Components\TextInput::make('buyer_remark')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('seller_message')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('prepay_id')
                    ->required()
                    ->maxLength(64),
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

    public static function getWidgets(): array
    {
        return [
            MallOrderStatOverview::class
        ];
    }
}
