<?php

namespace App\Filament\Resources\Customer;

use App\Filament\Resources\Customer\CustomerResource\Pages;
use App\Filament\Resources\Customer\CustomerResource\RelationManagers\BalancesRecordsRelationManager;
use App\Filament\Resources\Customer\CustomerResource\RelationManagers\PointsRecordsRelationManager;
use App\Models\Customer\Customer;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Exception;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = '会员管理';

    protected static ?string $navigationGroup = '会员';

    protected static ?string $modelLabel = '会员';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'view',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->nickname ?? '-';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nickname'];
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nickname')->searchable()->toggleable()->label('名称'),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->circular()
                    ->toggleable()
                    ->label('头像'),
                Tables\Columns\TextColumn::make('balance')
                    ->prefix('￥')
                    ->label('余额')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('points')->toggleable()->label('积分'),
                Tables\Columns\TextColumn::make('created_at')->toggleable(true, true)->label('注册时间'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->recordUrl(null)->defaultSort('id', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Fieldset::make('用户信息')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('nickname')->label('名称'),
                                //Infolists\Components\ImageEntry::make('avatar_url')->circular()->label('头像'),
                                Infolists\Components\TextEntry::make('balance')->prefix('￥')->label('余额'),
                                Infolists\Components\TextEntry::make('points')->label('积分'),
                                Infolists\Components\TextEntry::make('created_at')->label('注册时间'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BalancesRecordsRelationManager::class,
            PointsRecordsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Customer::query()->count();
    }
}
